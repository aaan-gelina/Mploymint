<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ./login.php");
    exit();
}
require_once "./php/joblist-function.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        <input type="text" class="search-input" placeholder="Search for job title">
      </div>
      <?php if ($_SESSION['type'] === 'company'): ?>
        <h2>My Job Postings</h2>
        <p>Manage the jobs you have posted below.</p>
      <?php else: ?>
        <h2>Available Job Listings</h2>
        <p>Explore opportunities posted by professionals and companies.</p>
      <?php endif; ?>
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
                    📍 <?= $job['location'] ?>
                    &nbsp; 🕒 <?= $job['type'] ?>
                    &nbsp; 💰 <?= $job['salary'] ?>
                  </p>
                </div>
              </div>
              <?php if ($_SESSION['type'] === 'company'): ?>
                <div class="action-buttons">
                  <a href="./job.php?jid=<?= $job['jid'] ?>" class="apply-btn">View</a>
                  <a href="./applicant_list.php?jid=<?= $job['jid'] ?>" class="apply-btn">Applicants</a>
                  <form method="POST" action="./php/joblist-function.php" style="display:inline;">
                    <input type="hidden" name="delete_jid" value="<?= $job['jid'] ?>">
                    <button type="submit" class="apply-btn">Delete</button>
                  </form>
                </div>
              <?php else: ?>
                <?php if (in_array($job['jid'], $applied_jobs)): ?>
                  <p class="applied-label">✅ Applied</p>
                <?php else: ?>
                  <div class="action-buttons">
                    <a href="./job.php?jid=<?= $job['jid'] ?>" class="apply-btn">Detail</a>
                    <form method="POST" action="./php/joblist-function.php">
                      <input type="hidden" name="apply_jid" value="<?= $job['jid'] ?>">
                      <button type="submit" class="apply-btn">Apply</button>
                    </form>
                  </div>
                <?php endif; ?>
              <?php endif; ?>
            </div>
        <?php 
          }
        } else {
          echo "<p>No jobs posted yet.</p>";
        }
        ?>
      </div>
    </main>
  </div>

  <div class="footer"><br></div>
  <script src="./js/joblist.js"></script>
</body>
</html>
