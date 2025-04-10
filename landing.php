<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mploymint</title>
    <link rel="stylesheet" href="./css/landing.css">
</head>
<body>
    <?php include "./top-navbar.php"; ?>
    <?php include "./php/landing-load-function.php"; ?>

    <div class="hero">
        <div class="hero-text">
            <h2>Find A <span class="highlight">Job</span> That <br>
                <span class="highlight-green">Matches</span> Your <span class="highlight">Passion</span>
            </h2>
            <p>Hand-picked opportunities to work from home, remotely, freelance, full-time, part-time, contract and internships.</p>
            <div class="search-box">
                <input type="text" id="search-input" placeholder="Search by job title...">
            </div>
        </div>
    </div>

    <div class="jobs">
        <h3>All Popular Listed Jobs</h3>
        <div id="job-list" class="job-list">
            <?php foreach ($jobs as $job): ?>
                <div class="job-card">
                    <div class="job-icon"><?= strtoupper(substr(trim($job["title"]), 0, 1)); ?></div>
                    <div class="job-details">
                        <h4><?= htmlspecialchars($job["title"]); ?></h4>
                        <p>📍 <?= htmlspecialchars($job["location"]); ?> | ⏳ <?= htmlspecialchars($job["type"]); ?> | 💰 <?= number_format($job["salary"]); ?></p>
                    </div>
                    <button class="btn-view">View Details</button>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="btn-more" id="load-more-btn">View More</button>
    </div>
    <div class="footer">
      <br>
    </div>
    <script>
        const jobs = <?= json_encode($jobs); ?>;
    </script>
    <script src="./js/landing.js"></script>
</body>
</html>
