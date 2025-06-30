<?php
require_once 'database.php';

// Initialize response variables
$errors = [];
$success = false;
$registration_data = [];

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get database connection
    $database = new Database();
    $db = $database->getConnection();
    
    // Sanitize and validate input data
    $full_name = sanitize_input($_POST['full_name'] ?? '');
    $email = sanitize_input($_POST['email'] ?? '');
    $phone = sanitize_input($_POST['phone'] ?? '');
    $ticket_type = sanitize_input($_POST['ticket_type'] ?? '');
    $company_organization = sanitize_input($_POST['company_organization'] ?? '');
    $job_title = sanitize_input($_POST['job_title'] ?? '');
    $dietary_requirements = sanitize_input($_POST['dietary_requirements'] ?? '');
    $special_accommodations = sanitize_input($_POST['special_accommodations'] ?? '');
    $how_did_you_hear = sanitize_input($_POST['how_did_you_hear'] ?? '');
    $newsletter_subscribe = isset($_POST['newsletter_subscribe']) ? 1 : 0;
    $terms_accepted = isset($_POST['terms_accepted']) ? 1 : 0;
    
    // Validation
    if (empty($full_name)) {
        $errors[] = "Full name is required";
    } elseif (strlen($full_name) > 100) {
        $errors[] = "Full name must be less than 100 characters";
    }
    
    if (empty($email)) {
        $errors[] = "Email address is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    } elseif (strlen($email) > 100) {
        $errors[] = "Email address must be less than 100 characters";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    } elseif (!preg_match('/^[0-9+\-\s()]+$/', $phone)) {
        $errors[] = "Please enter a valid phone number";
    } elseif (strlen($phone) > 20) {
        $errors[] = "Phone number must be less than 20 characters";
    }
    
    if (empty($ticket_type)) {
        $errors[] = "Please select a ticket type";
    } elseif (!in_array($ticket_type, ['Standard', 'VIP'])) {
        $errors[] = "Invalid ticket type selected";
    }
    
    if (!$terms_accepted) {
        $errors[] = "You must accept the terms and conditions";
    }
    
    // Check if email already exists for this event
    if (empty($errors)) {
        try {
            $stmt = $db->prepare("SELECT id FROM registered_users WHERE email = ? AND event_id = 1");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                $errors[] = "This email address is already registered for the event";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: Unable to check existing registrations";
            error_log("Database error: " . $e->getMessage());
        }
    }
    
    // If no errors, proceed with registration
    if (empty($errors)) {
        try {
            // Generate unique confirmation code
            $confirmation_code = generateConfirmationCode($db);
            
            // Prepare insertion query
            $query = "INSERT INTO registered_users (
                event_id, full_name, email, phone, ticket_type, 
                company_organization, job_title, dietary_requirements, 
                special_accommodations, how_did_you_hear, 
                newsletter_subscribe, terms_accepted, confirmation_code
            ) VALUES (
                1, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";
            
            $stmt = $db->prepare($query);
            $result = $stmt->execute([
                $full_name, $email, $phone, $ticket_type,
                $company_organization, $job_title, $dietary_requirements,
                $special_accommodations, $how_did_you_hear,
                $newsletter_subscribe, $terms_accepted, $confirmation_code
            ]);
            
            if ($result) {
                $registration_id = $db->lastInsertId();
                
                // Get the full registration data for confirmation
                $stmt = $db->prepare("
                    SELECT ru.*, e.name as event_name, e.date as event_date, 
                           e.location as event_location,
                           CASE 
                               WHEN ru.ticket_type = 'VIP' THEN e.vip_price 
                               ELSE e.standard_price 
                           END as ticket_price
                    FROM registered_users ru 
                    JOIN events e ON ru.event_id = e.id 
                    WHERE ru.id = ?
                ");
                $stmt->execute([$registration_id]);
                $registration_data = $stmt->fetch(PDO::FETCH_ASSOC);
                
                $success = true;
                
                // Store registration data in session for confirmation page
                $_SESSION['registration_data'] = $registration_data;
                
                // Send confirmation email (optional)
                sendConfirmationEmail($registration_data);
                
                // Redirect to confirmation page
                redirect_to('confirmation.php?id=' . $confirmation_code);
                
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
            
        } catch (PDOException $e) {
            $errors[] = "Database error: Registration could not be completed";
            error_log("Database error: " . $e->getMessage());
        }
    }
    
    // If there are errors, store them in session and redirect back
    if (!empty($errors)) {
        $_SESSION['registration_errors'] = $errors;
        $_SESSION['form_data'] = $_POST;
        redirect_to('register.php');
    }
    
} else {
    // If not POST request, redirect to registration form
    redirect_to('register.php');
}

/**
 * Generate unique confirmation code
 */
function generateConfirmationCode($db) {
    $prefix = 'CS2024';
    $attempts = 0;
    $max_attempts = 100;
    
    do {
        $attempts++;
        $number = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $code = $prefix . $number;
        
        $stmt = $db->prepare("SELECT id FROM registered_users WHERE confirmation_code = ?");
        $stmt->execute([$code]);
        $exists = $stmt->rowCount() > 0;
        
    } while ($exists && $attempts < $max_attempts);
    
    if ($attempts >= $max_attempts) {
        throw new Exception("Unable to generate unique confirmation code");
    }
    
    return $code;
}

/**
 * Send confirmation email (basic implementation)
 */
function sendConfirmationEmail($registration_data) {
    $to = $registration_data['email'];
    $subject = 'Registration Confirmation - Creative Summit 2024';
    
    $message = "
    <html>
    <head>
        <title>Registration Confirmation</title>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: linear-gradient(135deg, #0f766e 0%, #0d9488 100%); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 8px 8px; }
            .confirmation-code { background: #fb7185; color: white; padding: 15px; text-align: center; border-radius: 8px; font-size: 18px; font-weight: bold; margin: 20px 0; }
            .details { background: white; padding: 15px; border-radius: 8px; margin: 20px 0; }
            .footer { text-align: center; margin-top: 20px; font-size: 12px; color: #666; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🎯 Creative Summit 2024</h1>
                <h2>Registration Confirmed!</h2>
            </div>
            <div class='content'>
                <p>Dear " . htmlspecialchars($registration_data['full_name']) . ",</p>
                
                <p>Thank you for registering for Creative Summit 2024! We're excited to have you join us.</p>
                
                <div class='confirmation-code'>
                    Confirmation Code: " . htmlspecialchars($registration_data['confirmation_code']) . "
                </div>
                
                <div class='details'>
                    <h3>Event Details:</h3>
                    <p><strong>Event:</strong> " . htmlspecialchars($registration_data['event_name']) . "</p>
                    <p><strong>Date:</strong> " . date('F j, Y', strtotime($registration_data['event_date'])) . "</p>
                    <p><strong>Location:</strong> " . htmlspecialchars($registration_data['event_location']) . "</p>
                    <p><strong>Ticket Type:</strong> " . htmlspecialchars($registration_data['ticket_type']) . "</p>
                    <p><strong>Price:</strong> $" . number_format($registration_data['ticket_price'], 2) . "</p>
                </div>
                
                <div class='details'>
                    <h3>Your Registration Details:</h3>
                    <p><strong>Name:</strong> " . htmlspecialchars($registration_data['full_name']) . "</p>
                    <p><strong>Email:</strong> " . htmlspecialchars($registration_data['email']) . "</p>
                    <p><strong>Phone:</strong> " . htmlspecialchars($registration_data['phone']) . "</p>
                    " . (!empty($registration_data['company_organization']) ? "<p><strong>Company:</strong> " . htmlspecialchars($registration_data['company_organization']) . "</p>" : "") . "
                </div>
                
                <p><strong>Next Steps:</strong></p>
                <ul>
                    <li>Save this confirmation email for your records</li>
                    <li>Payment instructions will be sent separately</li>
                    <li>Event updates will be sent to this email address</li>
                    <li>Bring a valid ID and this confirmation code to the event</li>
                </ul>
                
                <p>If you have any questions, please contact us at info@creativesummit2024.com</p>
                
                <p>Looking forward to seeing you at the event!</p>
                
                <p>Best regards,<br>The Creative Summit 2024 Team</p>
            </div>
            <div class='footer'>
                <p>&copy; 2024 Creative Summit. All rights reserved.</p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    $headers = array(
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=iso-8859-1',
        'From: Creative Summit 2024 <noreply@creativesummit2024.com>',
        'Reply-To: info@creativesummit2024.com',
        'X-Mailer: PHP/' . phpversion())
    }