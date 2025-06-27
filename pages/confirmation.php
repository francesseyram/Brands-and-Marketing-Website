<?php
require_once '../config/database.php';

// Check if we have registration data
$registration_id = isset($_GET['id']) ? $_GET['id'] : (isset($_SESSION['registration_id']) ? $_SESSION['registration_id'] : null);
$attendee_data = null;

if ($registration_id) {
    $database = new Database();
    $db = $database->getConnection();
    
    $query = "SELECT a.*, e.name as event_name, e.date as event_date, e.location as event_location 
              FROM attendees a 
              JOIN events e ON a.event_id = e.id 
              WHERE a.id = :id";
    $stmt = $db->prepare($query);
    $stmt->bindParam(':id', $registration_id);
    $stmt->execute();
    $attendee_data = $stmt->fetch(PDO::FETCH_ASSOC);
}

// If no valid registration found, redirect to home
if (!$attendee_data) {
    redirect_to('../index.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmed - Creative Summit 2024</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="fullscreen-bg bg-cream-gradient">
    <div class="confirmation-container">
        <div class="success-header">
            <div class="success-icon">🎉</div>
            <h1>Registration Confirmed!</h1>
            <p>Welcome to Creative Summit 2024</p>
        </div>

        <div class="confirmation-content">
            <div class="registration-id">
                <h3>Your Registration ID</h3>
                <div class="id-number">#<?php echo str_pad($attendee_data['id'], 6, '0', STR_PAD_LEFT); ?></div>
            </div>

            <div class="registration-details">
                <div class="detail-row">
                    <span class="detail-label">Full Name</span>
                    <span class="detail-value"><?php echo htmlspecialchars($attendee_data['full_name']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email</span>
                    <span class="detail-value"><?php echo htmlspecialchars($attendee_data['email']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone</span>
                    <span class="detail-value"><?php echo htmlspecialchars($attendee_data['phone']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Event</span>
                    <span class="detail-value"><?php echo htmlspecialchars($attendee_data['event_name']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value"><?php echo date('F j, Y', strtotime($attendee_data['event_date'])); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Location</span>
                    <span class="detail-value"><?php echo htmlspecialchars($attendee_data['event_location']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Ticket Type</span>
                    <span class="detail-value"><?php echo htmlspecialchars($attendee_data['ticket_type']); ?></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Registration Date</span>
                    <span class="detail-value"><?php echo date('M j, Y g:i A', strtotime($attendee_data['registration_date'])); ?></span>
                </div>
            </div>

            <div class="next-steps">
                <h3>📋 What's Next?</h3>
                <ul class="steps-list">
                    <li>
                        <span class="step-number">1</span>
                        <span>Check your email for a detailed confirmation message</span>
                    </li>
                    <li>
                        <span class="step-number">2</span>
                        <span>Save your registration ID for future reference</span>
                    </li>
                    <li>
                        <span class="step-number">3</span>
                        <span>Mark your calendar for <?php echo date('F j, Y', strtotime($attendee_data['event_date'])); ?></span>
                    </li>
                    <li>
                        <span class="step-number">4</span>
                        <span>Follow us on social media for event updates</span>
                    </li>
                </ul>
            </div>

            <div class="action-buttons">
                <a href="../index.php" class="btn btn-primary">Back to Home</a>
                <a href="attendees.php" class="btn btn-secondary">View Attendees</a>
                <a href="javascript:printPage()" class="btn btn-outline">Print Confirmation</a>
            </div>

            <div class="social-share">
                <h4>Share the excitement!</h4>
                <div class="social-buttons">
                    <a href="https://twitter.com/intent/tweet?text=Just registered for Creative Summit 2024! 🎨✨" class="social-btn social-twitter">🐦</a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=" class="social-btn social-facebook">📘</a>
                    <a href="https://www.linkedin.com/sharing/share-offsite/" class="social-btn social-linkedin">💼</a>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/scripts.js"></script>
</body>
</html>
