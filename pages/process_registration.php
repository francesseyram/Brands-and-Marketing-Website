<?php
// Database configuration
$host = 'localhost';
$dbname = 'event_registration';
$username = 'root'; // Change this to your database username
$password = '';     // Change this to your database password

// Start session for error/success messages
session_start();

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Check if form was submitted via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: registration.php');
    exit;
}

// Validate and sanitize input data
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function validatePhone($phone) {
    // Allow numbers, spaces, hyphens, parentheses, and plus signs
    return preg_match('/^[0-9+\-\s()]+$/', $phone);
}

// Get form data and validate required fields
$errors = [];

// Required fields validation
$full_name = sanitizeInput($_POST['full_name'] ?? '');
if (empty($full_name)) {
    $errors[] = "Full name is required";
}

$email = sanitizeInput($_POST['email'] ?? '');
if (empty($email)) {
    $errors[] = "Email address is required";
} elseif (!validateEmail($email)) {
    $errors[] = "Please enter a valid email address";
}

$phone = sanitizeInput($_POST['phone'] ?? '');
if (empty($phone)) {
    $errors[] = "Phone number is required";
} elseif (!validatePhone($phone)) {
    $errors[] = "Please enter a valid phone number";
}

$ticket_type = sanitizeInput($_POST['ticket_type'] ?? '');
if (empty($ticket_type)) {
    $errors[] = "Please select a ticket type";
} elseif (!in_array($ticket_type, ['Standard', 'VIP'])) {
    $errors[] = "Invalid ticket type selected";
}

// Terms acceptance validation
$terms_accepted = isset($_POST['terms_accepted']) ? 1 : 0;
if (!$terms_accepted) {
    $errors[] = "You must accept the Terms and Conditions";
}

// Check for existing email registration
if (empty($errors)) {
    try {
        $checkEmailStmt = $pdo->prepare("SELECT id FROM attendees WHERE email = ? AND event_id = 1");
        $checkEmailStmt->execute([$email]);
        
        if ($checkEmailStmt->rowCount() > 0) {
            $errors[] = "This email address is already registered for the event";
        }
    } catch (PDOException $e) {
        $errors[] = "Database error occurred while checking email";
        error_log("Database error: " . $e->getMessage());
    }
}

// If there are validation errors, redirect back with errors
if (!empty($errors)) {
    $_SESSION['registration_errors'] = $errors;
    $_SESSION['form_data'] = $_POST; // Preserve form data
    header('Location: registration.php');
    exit;
}

// Insert into attendees table
try {
    $sql = "INSERT INTO attendees (event_id, full_name, email, phone, ticket_type, registration_date) 
            VALUES (1, :full_name, :email, :phone, :ticket_type, NOW())";
    
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        ':full_name' => $full_name,
        ':email' => $email,
        ':phone' => $phone,
        ':ticket_type' => $ticket_type
    ]);
    
    if ($result) {
        $registration_id = $pdo->lastInsertId();
        
        // Set success message
        $_SESSION['registration_success'] = "Registration successful! Your registration ID is: #" . str_pad($registration_id, 6, '0', STR_PAD_LEFT);
        $_SESSION['registration_details'] = [
            'name' => $full_name,
            'email' => $email,
            'ticket_type' => $ticket_type,
            'event_name' => 'Creative Summit 2024',
            'event_date' => 'August 15, 2024',
            'event_location' => 'Austin Convention Center'
        ];
        
        // Clear any old form data
        unset($_SESSION['form_data']);
        
        // Send confirmation email (optional - you can implement this)
        // sendConfirmationEmail($email, $full_name, $registration_id);
        
        // Redirect to success page or back to registration with success message
        header('Location: registration.php?success=1');
        exit;
        
    } else {
        throw new Exception("Failed to insert registration data");
    }
    
} catch (PDOException $e) {
    error_log("Database insertion error: " . $e->getMessage());
    $_SESSION['registration_errors'] = ["Registration failed. Please try again."];
    header('Location: registration.php');
    exit;
} catch (Exception $e) {
    error_log("General error: " . $e->getMessage());
    $_SESSION['registration_errors'] = ["An unexpected error occurred. Please try again."];
    header('Location: registration.php');
    exit;
}

// Optional: Email confirmation function
function sendConfirmationEmail($email, $name, $registration_id) {
    $subject = "Creative Summit 2024 - Registration Confirmation";
    $message = "
    Dear $name,
    
    Thank you for registering for Creative Summit 2024!
    
    Registration Details:
    - Registration ID: #" . str_pad($registration_id, 6, '0', STR_PAD_LEFT) . "
    - Event: Creative Summit 2024
    - Date: August 15, 2024
    - Location: Austin Convention Center
    
    We look forward to seeing you at the event!
    
    Best regards,
    Creative Summit Team
    ";
    
    $headers = "From: no-reply@creativesummit.com\r\n";
    $headers .= "Reply-To: info@creativesummit.com\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
    
    // Uncomment the line below to actually send emails
    // mail($email, $subject, $message, $headers);
}
?>