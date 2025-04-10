<?php
$error = "";
if (isset($_GET["error"])) {
  if ($_GET["error"] == "email_taken") {
    $error = "This email is already registered.";
  } elseif ($_GET["error"] == "signup_failed") {
    $error = "Signup failed. Please try again.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mploymint Sign Up</title>
  <link rel="stylesheet" href="./css/signup.css">
</head>
<body>
  <div class="container">
    <div class="left-section">
        <h1 class="logo">Mploymint</h1>
        <div class="headline">
            Join Us and Find Your <span class="highlight">Dream Job</span> Today!
        </div>
    </div>

    <div class="right-section">
      <h2 class="welcome">Create Your Account</h2>

      <p class="login-text">Sign up with your details</p>

      <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

      <form class="signup-form" method="POST" action="./php/signup-function.php">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" placeholder="Enter your full name" required>
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" placeholder="Enter your email address" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Create a password" required>
        <label for="type">Account Type</label>
        <select id="type" name="type" required>
          <option value="jobseeker">Job Seeker</option>
          <option value="company">Company</option>
        </select>
        <button type="submit" class="signup-btn">Sign Up</button>
      </form>

      <p class="login-text">
        Already have an account? <a href="./login.php" class="login-link">Login</a>
      </p>
    </div>
  </div>
  <script src="./js/signup.js"></script>
</body>
</html>
