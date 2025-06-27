<?php
require_once 'config/database.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creative Summit 2024 - Event Registration</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="fullscreen-bg bg-teal-coral">
    <div class="hero">
        <h1>🎨 Creative Summit 2024</h1>
        <p>Welcome to the most creative event of the year! Explore our pages to see attendees, login, or create an account.</p>
        
        <div class="nav-buttons" style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap; margin-top: 2rem;">
            <a href="pages/attendees.php" class="btn btn-primary">View Attendees</a>
            <a href="pages/login.php" class="btn btn-secondary">Login</a>
            <a href="pages/signup.php" class="btn btn-secondary">Sign Up</a>
        </div>
    </div>

    <script src="assets/js/scripts.js"></script>
</body>
</html>
