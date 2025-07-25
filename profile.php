<?php
session_start();
include 'dbconnect.php';

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


$current_resume = null;
if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
    $query = "SELECT filename FROM resume WHERE uid = ? AND archive = 0 LIMIT 1";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $current_resume = $result->fetch_assoc();
        
        // Check if the file exists in the regular resumes directory
        $standard_path = 'uploads/resumes/' . $current_resume['filename'];
        $user_specific_path = 'uploads/user_' . $uid . '/' . $current_resume['filename'];
        
        // Set the actual path to use
        if (file_exists($standard_path)) {
            $current_resume['path'] = $standard_path;
        } elseif (file_exists($user_specific_path)) {
            $current_resume['path'] = $user_specific_path;
        } else {
            $current_resume['path'] = $standard_path; // Default to standard even if not found
        }
    }
    $stmt->close();
}


$resume_error_codes = ['upload', 'type', 'move', 'database', 'dir_create', 'invalid'];

$upload_dir = 'uploads/resumes/';

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

            <?php if (isset($_GET['success']) && $_GET['success'] !== 'resume'): ?>
                <div class="alert success">Profile updated successfully!</div>
            <?php endif; ?>

            <?php 
            if (isset($_GET['error']) && $_GET['error'] !== 'resume'): 
                $error_message = "Error updating profile. Please try again.";
                
                // More detailed error messages
                if ($_GET['error'] === 'database') {
                    $error_message = "Database error occurred. Please try again.";
                } elseif ($_GET['error'] === 'database_prepare') {
                    $error_message = "Error preparing database query. Please try again.";
                } elseif ($_GET['error'] === 'database_bind') {
                    $error_message = "Error binding parameters. Please try again.";
                } elseif ($_GET['error'] === 'database_execute') {
                    $error_message = "Error executing database query. Please try again.";
                } elseif ($_GET['error'] === 'session') {
                    $error_message = "Session error. Please login again.";
                } elseif ($_GET['error'] === 'db_connect') {
                    $error_message = "Database connection failed. Please contact administrator.";
                } elseif ($_GET['error'] === 'db_include') {
                    $error_message = "Database configuration error. Please contact administrator.";
                } elseif ($_GET['error'] === 'exception') {
                    $error_message = "An unexpected error occurred. Please try again.";
                }
            ?>
                <div class="alert error"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <?php if ($view_only && $viewing_user): ?>
                <!-- View-only display for applicant profile -->
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="avatar-large">
                            <?php echo strtoupper(substr($viewing_user['name'], 0, 1)); ?>
                        </div>
                        <div class="profile-info">
                            <h3><?php echo $viewing_user['name']; ?></h3>
                            <p><?php echo $viewing_user['email']; ?></p>
                        </div>
                    </div>
                    
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
                </div>
            <?php else: ?>
                <!-- Editable profile form -->
                <div class="profile-card">
                    <div class="profile-header">
                        <div class="avatar-large">
                            <?php echo strtoupper($_SESSION['name'][0]); ?>
                        </div>
                        <div class="profile-info">
                            <h3><?php echo $_SESSION['name']; ?></h3>
                            <p><?php echo $_SESSION['email']; ?></p>
                        </div>
                    </div>

                    <form class="profile-form" method="POST" action="./php/profile-function.php">
                        <div class="form-group">
                            <label><?php echo $_SESSION['type'] === 'company' ? 'Company Name' : 'Full Name'; ?></label>
                            <input type="text" value="<?php echo $_SESSION['name']; ?>" readonly class="readonly-field">
                        </div>

                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" value="<?php echo $_SESSION['email']; ?>" readonly class="readonly-field">
                        </div>

                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" value="<?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : ''; ?>" placeholder="Enter your phone number">
                        </div>

                        <div class="form-group">
                            <label for="location">Location</label>
                            <input type="text" id="location" name="location" value="<?php echo isset($_SESSION['location']) ? htmlspecialchars($_SESSION['location']) : ''; ?>" placeholder="Enter your location">
                        </div>

                        <?php if ($_SESSION['type'] === 'company'): ?>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" name="description" rows="4" placeholder="Describe your company"><?php echo isset($_SESSION['description']) ? htmlspecialchars($_SESSION['description']) : ''; ?></textarea>
                        </div>
                        <?php else: ?>
                        <div class="form-group">
                            <label for="bio">Bio</label>
                            <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself"><?php echo isset($_SESSION['bio']) ? htmlspecialchars($_SESSION['bio']) : ''; ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="skills">Skills (comma separated)</label>
                            <input type="text" id="skills" name="skills" value="<?php echo isset($_SESSION['skills']) ? htmlspecialchars($_SESSION['skills']) : ''; ?>" placeholder="e.g. JavaScript, PHP, MySQL">
                        </div>
                        <?php endif; ?>

                        <button type="submit" class="save-btn">Save Changes</button>
                    </form>
                </div>
            <?php endif; ?>

            <?php if ($_SESSION['type'] !== 'company' && !$view_only): ?>
                <div class="profile-section">
                    <h3>Resume/CV</h3>
                    <p>Upload your resume in PDF format (.pdf)</p>

                    <?php 
                    if (isset($_GET['error']) && in_array($_GET['error'], $resume_error_codes)): 
                    ?>
                        <div class="alert error">
                            <?php
                            switch($_GET['error']) {
                                case 'upload':
                                    echo 'Error during file upload process. Please try again.';
                                    break;
                                case 'type':
                                    echo 'Invalid file type. Please upload PDF only.';
                                    break;
                                case 'move':
                                    echo 'Error saving file. Please check permissions or try again.';
                                    break;
                                case 'database':
                                    echo 'Error saving resume information to the database. Please try again.';
                                    break;
                                case 'dir_create':
                                case 'dir_missing':
                                case 'dir_permissions':
                                    echo 'Error with server directories. Please contact administrator.';
                                    break;
                                case 'invalid':
                                    echo 'Invalid request or no file uploaded. Please select a file and try again.';
                                    break;
                                default:
                                    echo 'An unknown error occurred during resume upload. Please try again.';
                            }
                            ?>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($_GET['success']) && $_GET['success'] === 'resume'): ?>
                        <div class="alert success">Resume uploaded successfully!</div>
                    <?php endif; ?>

                    <?php if ($current_resume): ?>
                        <div class="current-resume">
                            <p>Current Resume: <a href="<?php echo htmlspecialchars($current_resume['path']); ?>" target="_blank"><?php echo htmlspecialchars($current_resume['filename']); ?></a></p>
                        </div>
                    <?php else: ?>
                        <p>No resume uploaded yet.</p> 
                    <?php endif; ?>

                    <div class="resume-upload">
                        <form action="./php/upload-resume.php" method="POST" enctype="multipart/form-data">
                            <label for="resume" class="file-label">Choose a file or drag it here</label>
                            <input type="file" id="resume" name="resume" accept=".pdf" required>
                            <button type="submit" class="upload-btn">
                                <i class="fas fa-upload"></i> Upload New Resume
                            </button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>
        </main>
    </div>

    <div class="footer">
        <br>
    </div>

    <script src="js/profile.js"></script>
</body>
</html>
