<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$form_data = array(
    'company_name' => '',
    'company_website' => '',
    'job_title' => '',
    'job_category' => '',
    'job_type' => '',
    'job_location' => '',
    'salary_range' => '',
    'experience' => '',
    'qualification' => '',
    'application_deadline' => '',
    'application_link' => '',
    'job_description' => ''
);

if (isset($_SESSION['form_data'])) {
    $form_data = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
}

$error = "";
if (isset($_GET["error"])) {
    if ($_GET["error"] == "job_creation_failed") {
        $error = isset($_GET["message"]) ? urldecode($_GET["message"]) : "Failed to create job posting. Please try again.";
    } elseif ($_GET["error"] == "database_connection_failed") {
        $error = "Database connection failed. Please try again later.";
    } elseif ($_GET["error"] == "session_expired") {
        $error = "Your session has expired. Please log in again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mploymint</title>
    <link rel="stylesheet" href="./css/createjob.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <?php include "top-navbar.php"; ?>

    <div class="container">
        <main class="content">
            <h2>Create a Job</h2>
            <?php if (!empty($error)) echo "<p class='error-message'>$error</p>"; ?>
            
            <form action="php/createjob-function.php" method="POST" onsubmit="return validateForm()">
                <div class="form-row">
                    <label for="company_name">Company Name</label>
                    <input type="text" id="company_name" name="company_name" placeholder="Company Name" required 
                           value="<?php echo htmlspecialchars($form_data['company_name']); ?>">

                    <label for="company_website">Company Website</label>
                    <input type="url" id="company_website" name="company_website" placeholder="Company Website Link" required
                           value="<?php echo htmlspecialchars($form_data['company_website']); ?>">
                </div>
                <label for="job_title">Job Title</label>
                <input type="text" id="job_title" name="job_title" placeholder="Job Title" required
                       value="<?php echo htmlspecialchars($form_data['job_title']); ?>">

                <div class="form-row">
                    <label for="job_category">Job Category</label>
                    <select id="job_category" name="job_category" required>
                        <option value="">Select a category</option>
                        <?php
                        $categories = array('technology', 'business', 'healthcare', 'education');
                        foreach ($categories as $category) {
                            $selected = ($form_data['job_category'] === $category) ? 'selected' : '';
                            echo "<option value=\"$category\" $selected>" . ucfirst($category) . "</option>";
                        }
                        ?>
                    </select>

                    <label for="job_type">Job Type</label>
                    <select id="job_type" name="job_type" required>
                        <option value="">Select job type</option>
                        <?php
                        $types = array('full_time' => 'Full Time', 'part_time' => 'Part Time', 'contract' => 'Contract');
                        foreach ($types as $value => $label) {
                            $selected = ($form_data['job_type'] === $value) ? 'selected' : '';
                            echo "<option value=\"$value\" $selected>$label</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-row double-input-row">
                    <div>
                        <label for="job_location">Job Location</label>
                        <input type="text" id="job_location" name="job_location" placeholder="Location" required
                               value="<?php echo htmlspecialchars($form_data['job_location']); ?>">
                    </div>
                    <div>
                        <label for="salary_range">Salary Range</label>
                        <input type="number" id="salary_range" name="salary_range" placeholder="Enter salary amount" required min="0"
                               value="<?php echo htmlspecialchars($form_data['salary_range']); ?>">
                    </div>
                </div>

                <label for="experience">Experience</label>
                <input type="text" id="experience" name="experience" placeholder="Enter number of years (e.g., 2)" required
                       value="<?php echo htmlspecialchars($form_data['experience']); ?>">

                <label for="qualification">Qualification</label>
                <input type="text" id="qualification" name="qualification" placeholder="e.g., Bachelor's degree" required
                       value="<?php echo htmlspecialchars($form_data['qualification']); ?>">

                <label for="application_deadline">Application Deadline</label>
                <input type="date" id="application_deadline" name="application_deadline" required min="<?php echo date('Y-m-d'); ?>"
                       value="<?php echo htmlspecialchars($form_data['application_deadline']); ?>">

                <label for="application_link">Application Link</label>
                <input type="url" id="application_link" name="application_link" placeholder="Job application link url" required
                       value="<?php echo htmlspecialchars($form_data['application_link']); ?>">

                <label for="job_description">Job Description</label>
                <textarea id="job_description" name="job_description" placeholder="Enter detailed job description..." required><?php echo htmlspecialchars($form_data['job_description']); ?></textarea>

                <button type="submit">Post Job</button>
            </form>
        </main>
    </div>
    <div class="footer">
        <br>
    </div>
    <script>
        function validateForm() {
            const salary = document.getElementById('salary_range').value;
            
            if (salary <= 0) {
                alert('Please enter a valid salary amount greater than 0');
                return false;
            }
            
            const deadline = new Date(document.getElementById('application_deadline').value);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (deadline < today) {
                alert('Application deadline cannot be in the past');
                return false;
            }

            const experience = document.getElementById('experience').value.toLowerCase();
            if (!experience.includes('year')) {
                const expNum = parseInt(experience);
                if (!isNaN(expNum)) {
                    document.getElementById('experience').value = expNum + ' years';
                }
            }
            
            return true;
        }
    </script>
</body>
</html> 