<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: ../login.php");
  exit();
}
require_once "./php/my_joblist-function.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Mploymint</title>
  <link rel="stylesheet" href="./css/discussion.css">
  <link rel="stylesheet" href="./css/joblist.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
  <?php include "./top-navbar.php"; ?>
  <button class="menu-toggle" id="menu-toggle"><i class="fas fa-bars"></i></button>

  <div class="container">
    <?php include "./sidebar.php"; ?>

    <main class="forum">
      <div class="search-bar">
        <input type="text" class="search-input" placeholder="Search your job title">
      </div>

      <h2>My Applied Jobs</h2>
      <p>Review and track your applied jobs</p>

      <div class="forum-posts">
        <?php 
        if (count($jobs) > 0) {
          foreach ($jobs as $job) { ?>
            <div class="post">
              <div class="post-header">
                <div class="avatar-initials"><?= strtoupper(substr($job['title'], 0, 1)) ?></div>
                <div>
                  <h4><?= $job['title'] ?></h4>
                  <p>
                    🏢 <?= $job['company_name'] ?><br>
                    📌 Status: <?= ucfirst($job['status']) ?>
                  </p>
                </div>
              </div>
              <div class="action-buttons">
                <a href="./job.php?jid=<?= $job['jid'] ?>" class="apply-btn">Detail</a>
                <form method="POST" action="./php/my_joblist-function.php" onsubmit="return confirm('Cancel this application?');">
                  <input type="hidden" name="cancel_jid" value="<?= $job['jid'] ?>">
                  <button type="submit" class="apply-btn cancel">Cancel</button>
                </form>
              </div>
            </div>
        <?php 
          }
        } else {
          echo "<p>You haven't applied to any jobs yet.</p>";
        }
        ?>
      </div>
    </main>
  </div>

  <div class="footer"><br></div>
  <script src="./js/joblist.js"></script>
</body>
</html>
