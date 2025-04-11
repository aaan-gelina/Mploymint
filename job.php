<?php
    session_start();
    include 'dbconnect.php';
    $requs = null;
    $resps = null;

    //Validate jid
    if (isset($_GET['jid']) && is_numeric($_GET['jid'])) {
        $jid = intval($_GET['jid']);

        //Fetch job from database using jid
        $stmt = $db->prepare("
            SELECT job.*, user.name AS company_name
            FROM job 
            LEFT JOIN user ON job.cid = user.uid
            WHERE job.jid = ? 
            LIMIT 1
        ");
        $stmt->bind_param("i", $jid);
        $stmt->execute();
        $result = $stmt->get_result();
        $job = $result->fetch_assoc();

        //Display error if no job is found
        if (!$job) {
            echo "<p>Job not found.</p>";
        }
        else {
            $requs = !empty($job['requs']) ? explode('`', $job['requs']) : [];
            $resps = !empty($job['resps']) ? explode('`', $job['resps']) : [];
        }

        $stmt->close();

    } else {
        echo "<p>Invalid job ID.</p>";
    }
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Job Description</title>
    <link rel="stylesheet" href="./css/job.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
  </head>
  <body>
    <?php include "top-navbar.php"; ?>
    <button class="menu-toggle" id="menu-toggle"><i class="fas fa-bars"></i></button>
    <div class="cont">
        <?php include "sidebar.php"; ?>
        <main class="layout">
            <div class="page-title">
                <h2 class="page-heading"><?= htmlspecialchars($job['title']); ?></h2>
            </div>
            <div class="button-container">
            <button class="button" id="view-company-btn" data-cid="<?= htmlspecialchars($job['cid']); ?>">View Company</button>
                <?php if (isset($_SESSION['uid'])): ?>
                    <button id="apply-btn" class="button apply" 
                        data-jid="<?= htmlspecialchars($job['jid']); ?>" 
                        data-uid="<?= htmlspecialchars($_SESSION['uid']); ?>" 
                        data-cid="<?= htmlspecialchars($job['cid']); ?>">
                        Apply Now
                    </button>
                <?php else: ?>
                    <a href="/Mploymint/login.php" class="button apply">Login to Apply</a>
                <?php endif; ?>
            </div>
            <div class="container">
                <div class="attr-container">
                    <span class="attr">
                        <label>Company:</label>
                        <p><?= htmlspecialchars($job['company_name'] ?? 'Unknown'); ?></p>
                    </span>
                    <?php if (isset($job['curl']) && !empty(trim($job['curl']))): ?>
                        <span class="attr">
                            <label>Company Website:</label>
                            <p><a href="https://<?= htmlspecialchars($job['curl']); ?>" target="_blank"><?= htmlspecialchars($job['curl']); ?></a></p>
                        </span>
                    <?php endif; ?>
                    <span class="attr">
                        <label>Position:</label>
                        <p><?= htmlspecialchars($job['category']); ?></p>
                    </span>
                    <?php if (isset($job['experience']) && !empty(trim($job['experience']))): ?>
                        <span class="attr">
                            <label>Required Experience:</label>
                            <p><?= htmlspecialchars($job['experience']); ?></p>
                        </span>
                    <?php endif; ?>
                    <span class="attr">
                        <label>Location:</label>
                        <p><?= htmlspecialchars($job['location']); ?></p>
                    </span>
                    <?php if (isset($job['appdeadline']) && !empty(trim($job['appdeadline']))): ?>
                        <span class="attr">
                            <label>Application Deadline:</label>
                            <p><?= htmlspecialchars($job['appdeadline']); ?></p>
                        </span>
                    <?php endif; ?>
                    <span class="attr">
                        <label>Salary:</label>
                        <p>$<?= htmlspecialchars($job['salary']); ?></p>
                    </span>
                    <?php if (isset($job['appurl']) && !empty(trim($job['appurl']))): ?>
                        <span class="attr">
                            <label>External Application Link:</label>
                            <p><a href="https://<?= htmlspecialchars($job['appurl']); ?>" target="_blank"><?= htmlspecialchars($job['appurl']); ?></a></p>
                        </span>
                    <?php endif; ?>
                    <?php if (isset($job['description']) && !empty(trim($job['description']))): ?>
                        <h3>Job Description</h3>
                        <p><?= htmlspecialchars($job['description']); ?></p>
                    <?php endif; ?>
                </div>
                <div class="requ-container">
                    <?php if (isset($job['requs']) && !empty(trim($job['requs']))): ?>
                        <h3>Requirements</h3>
                        <ul>
                            <?php if (!empty($requs)): ?>
                                <?php foreach ($requs as $req): ?>
                                    <li><?= htmlspecialchars(trim($req)); ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                    <?php if (isset($job['resps']) && !empty(trim($job['resps']))): ?>
                    <h3>Responsibilities</h3>
                        <ul>
                            <?php if (!empty($resps)): ?>
                                <?php foreach ($resps as $resp): ?>
                                    <li><?= htmlspecialchars(trim($resp)); ?></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
    <div class="footer">
      <br>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const viewCompanyBtn = document.getElementById("view-company-btn");

            if (viewCompanyBtn) {
                viewCompanyBtn.addEventListener("click", function() {
                    const cid = viewCompanyBtn.getAttribute("data-cid");
                    window.location.href = `profile.php?cid=${encodeURIComponent(cid)}`;
                });
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const applyBtn = document.getElementById("apply-btn");

            if (applyBtn) {
                applyBtn.addEventListener("click", function() {
                    const jid = applyBtn.getAttribute("data-jid");
                    const uid = applyBtn.getAttribute("data-uid");
                    const cid = applyBtn.getAttribute("data-cid");

                    console.log("Applying for job:", jid, "User:", uid, "Company:", cid);

                    fetch("/Mploymint/php/apply.php", {
                        method: "POST",
                        headers: { "Content-Type": "application/x-www-form-urlencoded" },
                        body: `jid=${jid}&uid=${uid}&cid=${cid}`
                    })
                    .then(response => response.json())
                    .then(data => {
                        alert(data.message);
                    })
                    .catch(error => console.error("Error:", error));
                });
            }
        });
    </script>
  </body>
</html>