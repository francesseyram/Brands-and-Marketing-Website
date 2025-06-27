<?php
require_once '../config/database.php';

$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = sanitize_input($_POST['full_name']);
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);
    $confirm_password = sanitize_input($_POST['confirm_password']);
    
    // Validation
    if (empty($full_name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error_message = 'Password must be at least 6 characters long.';
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        // Check if email already exists
        $checkQuery = "SELECT id FROM users WHERE email = :email";
        $checkStmt = $db->prepare($checkQuery);
        $checkStmt->bindParam(':email', $email);
        $checkStmt->execute();
        
        if ($checkStmt->rowCount() > 0) {
            $error_message = 'An account with this email already exists.';
        } else {
            // Insert new user
            $insertQuery = "INSERT INTO users (full_name, email, password) VALUES (:full_name, :email, :password)";
            $insertStmt = $db->prepare($insertQuery);
            $insertStmt->bindParam(':full_name', $full_name);
            $insertStmt->bindParam(':email', $email);
            $insertStmt->bindParam(':password', $password); // In production: password_hash($password, PASSWORD_DEFAULT)
            
            if ($insertStmt->execute()) {
                $success_message = 'Account created successfully! You can now sign in.';
                $full_name = $email = '';
            } else {
                $error_message = 'Failed to create account. Please try again.';
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Creative Summit 2024</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="fullscreen-bg bg-coral-teal">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo">🎨</div>
                <h1>Join Creative Summit</h1>
                <p>Create your account to get started</p>
            </div>

            <div class="auth-form">
                <?php if ($error_message): ?>
                    <div class="alert error">
                        ⚠️ <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <?php if ($success_message): ?>
                    <div class="alert success">
                        ✅ <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="" id="signupForm">
                    <div class="form-group">
                        <label for="full_name">Full Name</label>
                        <input type="text" id="full_name" name="full_name" required 
                               value="<?php echo isset($full_name) ? htmlspecialchars($full_name) : ''; ?>"
                               placeholder="Enter your full name">
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>"
                               placeholder="Enter your email">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <div class="password-toggle">
                                <input type="password" id="password" name="password" required 
                                       placeholder="Create password">
                                <button type="button" onclick="togglePassword('password')">👁️</button>
                            </div>
                            <div class="password-strength">
                                <div class="strength-bar">
                                    <div class="strength-fill" id="strengthBar"></div>
                                </div>
                                <span id="strengthText">Enter a password</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm Password</label>
                            <div class="password-toggle">
                                <input type="password" id="confirm_password" name="confirm_password" required 
                                       placeholder="Confirm your password">
                                <button type="button" onclick="togglePassword('confirm_password')">👁️</button>
                            </div>
                            <div id="passwordMatch" style="margin-top: 0.5rem; font-size: 0.9rem;"></div>
                        </div>
                    </div>

                

                    <button type="submit" class="btn btn-primary full-width" id="submitBtn" disabled>
                        Create Account
                    </button>
                </form>

                <div class="divider">
                    <span>or</span>
                </div>

                <a href="../index.php" class="btn btn-secondary full-width">
                    Back to Home
                </a>

                <div class="auth-link">
                    Already have an account? <a href="login.php">Sign in here</a>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/scripts.js"></script>
</body>
</html>
