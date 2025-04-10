<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/dbconnect.php';


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

// Check for view-only mode for companies viewing applicant profiles
$view_only = isset($_GET['view_only']) && $_GET['view_only'] === 'true';
$viewing_user = null;

// If view_only is true and uid is provided, load that user's profile
if ($view_only && isset($_GET['uid']) && is_numeric($_GET['uid'])) {
    $uid = intval($_GET['uid']);
    
    // Get user profile data
    $stmt = $db->prepare("SELECT * FROM user WHERE uid = ? LIMIT 1");
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $viewing_user = $result->fetch_assoc();
    } else {
        // User not found
        header("Location: applicant_list.php?error=usernotfound");
        exit();
    }
}

include_once "php/profile-function.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mploymint - <?php echo $view_only ? 'Applicant Profile' : 'My Profile'; ?></title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <?php include "top-navbar.php"; ?>
    <button class="menu-toggle" id="menu-toggle"><i class="fas fa-bars"></i></button>

    <div class="container profile-page">
        <?php include "sidebar.php"; ?>

        <main class="profile-content">
            <?php if ($view_only && $viewing_user): ?>
                <div class="view-only-header">
                    <a href="applicant_list.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Applicant List</a>
                    <h2>Applicant Profile</h2>
                    <p>Viewing profile information for this applicant</p>
                </div>
            <?php else: ?>
                <h2>My Profile</h2>
                <p>Manage your personal information and preferences</p>
            <?php endif; ?>

            <?php if (isset($_GET['success'])): ?>
                <div class="alert success">Profile updated successfully!</div>
            <?php endif; ?>

            <?php if (isset($_GET['error'])): ?>
                <div class="alert error">Error updating profile. Please try again.</div>
            <?php endif; ?>

            <div class="profile-card">
                <div class="profile-header">
                    <div class="avatar-large">
                        <?php 
                        if ($view_only && $viewing_user) {
                            echo strtoupper(substr($viewing_user['name'], 0, 1));
                        } else {
                            echo strtoupper($_SESSION['name'][0]);
                        }
                        ?>
                    </div>
                    <div class="profile-info">
                        <h3>
                            <?php echo $view_only && $viewing_user ? $viewing_user['name'] : $_SESSION['name']; ?>
                        </h3>
                        <p>
                            <?php echo $view_only && $viewing_user ? $viewing_user['email'] : $_SESSION['email']; ?>
                        </p>
                    </div>
                </div>

                <?php if ($view_only && $viewing_user): ?>
                <!-- View-only display for applicant profile -->
                <div class="profile-details">
                    <div class="detail-group">
                        <label>Full Name</label>
                        <p><?php echo $viewing_user['name']; ?></p>
                    </div>

                    <div class="detail-group">
                        <label>Email</label>
                        <p><?php echo $viewing_user['email']; ?></p>
                    </div>

                    <?php if (!empty($viewing_user['phone'])): ?>
                    <div class="detail-group">
                        <label>Phone Number</label>
                        <p><?php echo $viewing_user['phone']; ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($viewing_user['location'])): ?>
                    <div class="detail-group">
                        <label>Location</label>
                        <p><?php echo $viewing_user['location']; ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($viewing_user['description'])): ?>
                    <div class="detail-group">
                        <label>Bio</label>
                        <p><?php echo $viewing_user['description']; ?></p>
                    </div>
                    <?php endif; ?>

                    <?php if (!empty($viewing_user['skills'])): ?>
                    <div class="detail-group">
                        <label>Skills</label>
                        <p><?php echo $viewing_user['skills']; ?></p>
                    </div>
                    <?php endif; ?>
                </div>
                <?php else: ?>
                <!-- Editable form for own profile -->
                <form class="profile-form" method="POST" action="/Mploymint/php/profile-function.php">
                    <!-- Read-only fields -->
                    <div class="form-group">
                        <label>Full Name</label>
                        <input type="text" value="<?php echo $_SESSION['name']; ?>" readonly class="readonly-field">
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" value="<?php echo $_SESSION['email']; ?>" readonly class="readonly-field">
                    </div>

                   
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo isset($_SESSION['phone']) ? $_SESSION['phone'] : ''; ?>" placeholder="Enter your phone number">
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" value="<?php echo isset($_SESSION['location']) ? $_SESSION['location'] : ''; ?>" placeholder="Enter your location">
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself"><?php echo isset($_SESSION['bio']) ? $_SESSION['bio'] : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="skills">Skills (comma separated)</label>
                        <input type="text" id="skills" name="skills" value="<?php echo isset($_SESSION['skills']) ? $_SESSION['skills'] : ''; ?>" placeholder="e.g. JavaScript, PHP, MySQL">
                    </div>

                    <button type="submit" class="save-btn">Save Changes</button>
                </form>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <div class="footer">
        <br>
    </div>

    <script src="js/profile.js"></script>
</body>
</html>
