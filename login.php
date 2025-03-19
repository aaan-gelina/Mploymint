<?php
$error = "";
if (isset($_GET["error"])) {
    if ($_GET["error"] == "invalid_password") {
        $error = "Invalid password. Please try again.";
    } elseif ($_GET["error"] == "no_account") {
        $error = "No account with email.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mploymint Login</title>
    <link rel="stylesheet" href="./css/login.css">
</head>
<body>
    <div class="container">
        <div class="left-section">
            <h1 class="logo">Mploymint</h1>
            <div class="headline">
                Find A <span class="highlight">Job</span> That <br>
                <span class="highlight">Matches</span> Your Passion
            </div>
        </div>

        <div class="right-section">
            <h2 class="welcome">Welcome Back!</h2>

            <div class="toggle-buttons">
                <button id="jobSeekerBtn" class="active">Job Seeker</button>
                <button id="companyBtn">Company</button>
            </div>
        
            <p class="login-text">Or login with email</p>

            <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

            <form class="login-form" method="POST" action="./php/login-function.php">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter email address" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter password" required>
                <button type="submit" class="login-btn">Login</button>
            </form>

            <p class="signup-text">
                Don't have an account? <a href="signup.php" class="signup-link">Sign Up</a>
            </p>
        </div>
    </div>
    <script src="./js/login.js"></script>
</body>
</html>
