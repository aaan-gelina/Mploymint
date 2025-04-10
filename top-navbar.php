<?php
session_start();
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$user_type = $logged_in ? $_SESSION['type'] : null;
?>
<link rel="stylesheet" href="./css/top-navbar.css">
<header class="navbar">
  <h1 class="logo">Mploymint</h1>
  <nav>
    <?php if (!$logged_in): ?>
      <a href="./login.php" class="login-btn">Login</a>
    <?php else: ?>
      <a href="./php/logout-function.php" class="login-btn">Logout</a>
      <?php if ($user_type === 'company'): ?>
        <a href="./createjob.php" class="btn-post-job">Post a job</a>
      <?php endif; ?>
    <?php endif; ?>
  </nav>
</header>
