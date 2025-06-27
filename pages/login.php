<?php
require_once '../config/database.php';

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitize_input($_POST['email']);
    $password = sanitize_input($_POST['password']);
    
    if (empty($email) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } else {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && $password === $user['password']) { // In production, use password_verify()
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_email'] = $user['email'];
            
            redirect_to('attendees.php');
        } else {
            $error_message = 'Invalid email or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Creative Summit 2024</title>
    <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body class="fullscreen-bg bg-teal-coral">
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <div class="logo">🎨</div>
                <h1>Welcome Back</h1>
                <p>Sign in to Creative Summit 2024</p>
            </div>

            <div class="auth-form">
                <?php if ($error_message): ?>
                    <div class="alert error">
                        ⚠️ <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" name="email" required 
                               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                               placeholder="Enter your email">
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <div class="password-toggle">
                            <input type="password" id="password" name="password" required 
                                   placeholder="Enter your password">
                            <button type="button" onclick="togglePassword('password')">👁️</button>
                        </div>
                    </div>

                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember">
                            <label for="remember">Remember me</label>
                        </div>
                        <a href="#" class="forgot-password">Forgot password?</a>
                    </div>

                    <button type="submit" class="btn btn-primary full-width">
                        Sign In
                    </button>
                </form>

                <div class="divider">
                    <span>or</span>
                </div>

                <a href="../index.php" class="btn btn-secondary full-width">
                    Back to Home
                </a>

                <div class="auth-link">
                    Don't have an account? <a href="signup.php">Sign up here</a>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/scripts.js"></script>
</body>
</html>
