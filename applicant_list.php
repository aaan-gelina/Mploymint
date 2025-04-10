<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
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
    <link rel="stylesheet" href="css/applicant_list.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
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
                    <span><i class="fas fa-money-bill-wave"></i> ₹<?php echo number_format($job['salary']); ?></span>
                </div>
            </div>
            <?php endif; ?>

            <div class="search-bar">
                <input type="text" class="search-input" placeholder="Search applicants...">
            </div>

            <h3>Applicants</h3>
            <p>View and manage applications for this position</p>

            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
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
                            <td><?php echo htmlspecialchars($applicant['name']); ?></td>
                            <td><?php echo htmlspecialchars($applicant['email']); ?></td>
                            <td><a href="<?php echo htmlspecialchars($applicant['resume']); ?>" target="_blank">View</a></td>
                            <td><?php echo date('M d, Y', strtotime($applicant['applied_date'])); ?></td>
                            <td><?php echo htmlspecialchars($applicant['status']); ?></td>
                        </tr>
                    <?php 
                        }
                    } else {
                        echo '<tr><td colspan="5" style="text-align: center;">No applicants found for this position</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </main>
    </div>

    <div class="footer">
        <br>
    </div>

    <script src="js/applicant_list.js"></script>
</body>
</html> 