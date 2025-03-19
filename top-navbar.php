<?php
session_start();
$logged_in = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
?>

<header class="navbar">
  <h1 class="logo">Mploymint</h1>
  <nav>
    <?php if ($logged_in): ?>
      <a href="/Mploymint/php/logout-function.php" class="login-btn">Logout</a>
    <?php else: ?>
      <a href="/Mploymint/login.php" class="login-btn">Login</a>
    <?php endif; ?>
    <button class="btn-post-job">Post a job</button>
  </nav>
</header>
