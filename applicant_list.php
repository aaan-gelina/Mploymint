<?php
session_start();
include 'dbconnect.php';
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {

    header("Location: ./login.php");
    exit();
}
require_once "./php/applicant-list-function.php";

    header("Location: login.php");
    exit();
}
require_once "php/applicant-list-function.php";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mploymint</title>

    <link rel="stylesheet" href="./css/discussion.css">
    <link rel="stylesheet" href="./css/applicant_list.css">

    <link rel="stylesheet" href="css/applicant_list.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>

    <?php include "./top-navbar.php"; ?>
    <button class="menu-toggle" id="menu-toggle"><i class="fas fa-bars"></i></button>

    <div class="container">
        <?php include "./sidebar.php"; ?>
        <main class="forum">
            <?php if ($_SESSION['type'] !== 'company'): ?>
                <div class="alert alert-warning">
                    This page is only available for company accounts.
                </div>
            <?php elseif (!isset($_GET['jid']) && empty($company_jobs)): ?>
                <div class="alert alert-warning">
                    You haven't posted any jobs yet.
                </div>
            <?php elseif (isset($_GET['jid']) && !$job): ?>
                <div class="alert alert-warning">
                    The selected job could not be found.
                </div>
            <?php elseif (isset($_GET['jid']) && $job): ?>

    <?php include "top-navbar.php"; ?>

    <div class="container">
        <main class="content">
            <?php if ($job): ?>

            <div class="job-details">
                <h2><?php echo htmlspecialchars($job['title']); ?></h2>
                <div class="job-info">
                    <span><i class="fas fa-building"></i> <?php echo htmlspecialchars($job['company']); ?></span>
                    <span><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($job['location']); ?></span>
                    <span><i class="fas fa-clock"></i> <?php echo htmlspecialchars($job['type']); ?></span>

                    <span><i class="fas fa-money-bill-wave"></i> $<?php echo number_format($job['salary']); ?></span>
                </div>
                <a href="applicant_list.php" class="back-link"><i class="fas fa-arrow-left"></i> View All Applications</a>

                    <span><i class="fas fa-money-bill-wave"></i> ₹<?php echo number_format($job['salary']); ?></span>
                </div>

            </div>
            <?php endif; ?>

            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Search applicants...">
            </div>


            <?php if (isset($_GET['status_updated']) && $_GET['status_updated'] == 1): ?>
                <div class="alert alert-success">
                    Applicant status updated successfully!
                </div>
            <?php endif; ?>

            <?php if ($_SESSION['type'] === 'company'): ?>
            <h2><?php echo isset($_GET['jid']) ? "Applicants for Selected Job" : "All Applications"; ?></h2>
            <p>
                <?php echo isset($_GET['jid']) 
                    ? "View and manage applications for this position" 
                    : "View and manage applications for all your job postings"; ?>
            </p>

            <h3>Applicants</h3>
            <p>View and manage applications for this position</p>


            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>

                        <?php if (!isset($_GET['jid'])): ?>
                        <th>Job Position</th>
                        <?php endif; ?>
                        <th>Resume</th>
                        <th>Applied Date</th>
                        <th>Status</th>
                        <th>Actions</th>

                        <th>Resume</th>
                        <th>Applied Date</th>
                        <th>Status</th>

                    </tr>
                </thead>
                <tbody>
                    <?php 
                    if (count($applicants) > 0) {
                        foreach ($applicants as $applicant) {
                    ?>
                        <tr>

                            <td>
                                <a href="profile.php?uid=<?php echo $applicant['uid']; ?>&view_only=true" class="profile-link">
                                    <?php echo htmlspecialchars($applicant['name']); ?>
                                </a>
                            </td>
                            <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                            <?php if (!isset($_GET['jid'])): ?>
                            <td>
                                <a href="applicant_list.php?jid=<?php echo $applicant['jid']; ?>">
                                    <?php echo htmlspecialchars($applicant['job_title']); ?>
                                </a>
                            </td>
                            <?php endif; ?>
                            <td>
                                <?php if ($applicant['has_resume']): ?>
                                    <a href="<?php echo htmlspecialchars($applicant['resume']); ?>" target="_blank">View</a>
                                    <?php if (isset($applicant['resume_exists']) && !$applicant['resume_exists']): ?>
                                        <span class="no-resume">(File missing)</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span class="no-resume">Not Uploaded</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo !empty($applicant['applied_date']) ? date('M d, Y', strtotime($applicant['applied_date'])) : 'Unknown'; ?></td>
                            <td><?php echo htmlspecialchars($applicant['status']); ?></td>
                            <td>
                                <form method="POST" action="./php/applicant-list-function.php" class="status-form">
                                    <input type="hidden" name="aid" value="<?php echo $applicant['aid']; ?>">
                                    <input type="hidden" name="jid" value="<?php echo $applicant['jid']; ?>">
                                    <select name="status" class="status-select">
                                        <option value="pending" <?php echo ($applicant['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                                        <option value="reviewed" <?php echo ($applicant['status'] == 'reviewed') ? 'selected' : ''; ?>>Reviewed</option>
                                        <option value="shortlisted" <?php echo ($applicant['status'] == 'shortlisted') ? 'selected' : ''; ?>>Shortlisted</option>
                                        <option value="rejected" <?php echo ($applicant['status'] == 'rejected') ? 'selected' : ''; ?>>Rejected</option>
                                        <option value="hired" <?php echo ($applicant['status'] == 'hired') ? 'selected' : ''; ?>>Hired</option>
                                    </select>
                                    <button type="submit" name="update_status" class="update-btn">Update</button>
                                </form>
                            </td>

                            <td><?php echo htmlspecialchars($applicant['name']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($applicant['resume']); ?>" target="_blank">View</a></td>
                            <td><?php echo date('M d, Y', strtotime($applicant['applied_date'])); ?></td>
                            <td><?php echo htmlspecialchars($applicant['status']); ?></td>

                        </tr>
                    <?php 
                        }
                    } else {

                        $colspan = isset($_GET['jid']) ? 6 : 7;
                        echo '<tr><td colspan="' . $colspan . '" style="text-align: center;">No applicants found</td></tr>';

                        echo '<tr><td colspan="5" style="text-align: center;">No applicants found for this position</td></tr>';

                    }
                    ?>
                </tbody>
            </table>

            <?php else: ?>
            <h2>Job Applicants</h2>
            <p>This page is only available for company accounts.</p>
            <?php endif; ?>
        </main>
    </div>

    <div class="footer"><br></div>
    <script src="./js/applicant_list.js"></script>

        </main>
    </div>

    <div class="footer">
        <br>
    </div>

    <script src="js/applicant_list.js"></script>

</body>
</html> 