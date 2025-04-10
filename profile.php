<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/dbconnect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit();
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
    }
    $stmt->close();
}


$resume_error_codes = ['upload', 'type', 'move', 'database', 'dir_create', 'invalid'];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mploymint - Profile</title>
    <link rel="stylesheet" href="css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>

<body>
    <?php include "top-navbar.php"; ?>
    <button class="menu-toggle" id="menu-toggle"><i class="fas fa-bars"></i></button>

    <div class="container profile-page">
        <?php include "sidebar.php"; ?>

        <main class="profile-content">
            <h2>My Profile</h2>
            <p>Manage your personal information and preferences</p>

            <?php if (isset($_GET['success']) && $_GET['success'] !== 'resume'): ?>
                <div class="alert success">Profile updated successfully!</div>
            <?php endif; ?>

            <?php 
           
            if (isset($_GET['error']) && !in_array($_GET['error'], $resume_error_codes)): 
            ?>
                <div class="alert error">Error updating profile. Please try again.</div>
            <?php endif; ?>

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

                <form class="profile-form" method="POST" action="/Mploymint/php/profile-function.php">
                    
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
                        <input type="tel" id="phone" name="phone" value="<?php echo isset($_SESSION['phone']) ? htmlspecialchars($_SESSION['phone']) : ''; ?>" placeholder="Enter your phone number">
                    </div>

                    <div class="form-group">
                        <label for="location">Location</label>
                        <input type="text" id="location" name="location" value="<?php echo isset($_SESSION['location']) ? htmlspecialchars($_SESSION['location']) : ''; ?>" placeholder="Enter your location">
                    </div>

                    <div class="form-group">
                        <label for="bio">Bio</label>
                        <textarea id="bio" name="bio" rows="4" placeholder="Tell us about yourself"><?php echo isset($_SESSION['bio']) ? htmlspecialchars($_SESSION['bio']) : ''; ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="skills">Skills (comma separated)</label>
                        <input type="text" id="skills" name="skills" value="<?php echo isset($_SESSION['skills']) ? htmlspecialchars($_SESSION['skills']) : ''; ?>" placeholder="e.g. JavaScript, PHP, MySQL">
                    </div>

                    <button type="submit" class="save-btn">Save Changes</button>
                </form>
            </div>

     
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
                                echo 'Error creating upload directory. Please check server permissions.';
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
                        <p>Current Resume: <a href="uploads/resumes/<?php echo htmlspecialchars($current_resume['filename']); ?>" target="_blank"><?php echo htmlspecialchars($current_resume['filename']); ?></a></p>
                    </div>
                <?php else: ?>
                    <p>No resume uploaded yet.</p> 
                <?php endif; ?>

                <div class="resume-upload">
                    <form action="/Mploymint/php/upload-resume.php" method="POST" enctype="multipart/form-data">
                        <label for="resume" class="file-label">Choose a file or drag it here</label>
                        <input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx" required>
                        <button type="submit" class="upload-btn">
                            <i class="fas fa-upload"></i> Upload New Resume
                        </button>
                    </form>
                </div>
            </div>
           

        </main>
    </div>

    <div class="footer">
        <br>
    </div>

    <script src="js/profile.js"></script>
</body>
</html>
