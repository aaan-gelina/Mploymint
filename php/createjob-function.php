<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: ../createjob.php");
    exit();
}

$_SESSION['form_data'] = $_POST;

include '../dbconnect.php';

try {
    if (!isset($db)) {
        throw new Exception("Database connection variable not set");
    }
    
    if (!$db->ping()) {
        throw new Exception("Database connection lost: " . $db->error);
    }
    
    $company_name = trim($_POST["company_name"]);
    $company_website = trim($_POST["company_website"]);
    $job_title = trim($_POST["job_title"]);
    $job_category = trim($_POST["job_category"]);
    $job_type = trim($_POST["job_type"]);
    $job_location = trim($_POST["job_location"]);
    $salary = (int)trim($_POST["salary_range"]);
    
    $experience = trim($_POST["experience"]);
    if (is_numeric($experience)) {
        $experience .= ' years';
        $_SESSION['form_data']['experience'] = $experience;
    }
    
    $qualification = trim($_POST["qualification"]);
    $application_deadline = trim($_POST["application_deadline"]);
    $application_link = trim($_POST["application_link"]);
    $job_description = trim($_POST["job_description"]);
    
    error_log("Form data received: " . print_r($_POST, true));
    
    if (!isset($_SESSION["uid"]) || !isset($_SESSION["email"])) {
        throw new Exception("Session data missing - UID: " . isset($_SESSION["uid"]) . ", Email: " . isset($_SESSION["email"]));
    }
    
    $company_id = $_SESSION["uid"];
    
    error_log("Processing job creation with data: " . json_encode([
        'company_id' => $company_id,
        'company_name' => $company_name,
        'job_title' => $job_title,
        'salary' => $salary,
        'experience' => $experience,
        'deadline' => $application_deadline
    ]));

    if (empty($job_title) || empty($job_category) || empty($job_type) || 
        empty($job_location) || empty($salary) || empty($application_deadline)) {
        throw new Exception("Missing required fields");
    }

    $test_query = "SELECT 1";
    $test_result = $db->query($test_query);
    if (!$test_result) {
        throw new Exception("Database connection test failed: " . $db->error);
    }

    $query = "INSERT INTO job (cid, curl, title, category, type, location, salary, experience, appdeadline, appurl, description, requs, resps, status) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $statement = $db->prepare($query);
    if (!$statement) {
        throw new Exception("Prepare failed: " . $db->error);
    }
    
    $deadline_datetime = date('Y-m-d H:i:s', strtotime($application_deadline));
    
    $status = 'active';
    
    $bind_result = $statement->bind_param("issssssissssss", 
        $company_id,
        $company_website,
        $job_title,
        $job_category,
        $job_type,
        $job_location,
        $salary,
        $experience,
        $deadline_datetime,
        $application_link,
        $job_description,
        $qualification,
        $job_description,
        $status
    );
    
    if (!$bind_result) {
        throw new Exception("Bind failed: " . $statement->error);
    }
    
    if ($statement->execute()) {
        error_log("Job created successfully with ID: " . $statement->insert_id);
        
        $log_query = "INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value) 
                      VALUES (?, ?, 'create', 'Created new job posting', 'job', 'INSERT', 'N/A', ?)";
        
        $log_statement = $db->prepare($log_query);
        $new_value = "Job Title: " . $job_title . ", Company: " . $company_name;
        $log_statement->bind_param("iss", $_SESSION["uid"], $_SESSION["email"], $new_value);
        $log_statement->execute();
        
        unset($_SESSION['form_data']);
        
        header("Location: ../discussion.php?success=job_created");
        exit();
    } else {
        throw new Exception("Execute failed: " . $statement->error);
    }
} catch (Exception $e) {
    error_log("Job creation error: " . $e->getMessage());
    if (isset($db)) {
        error_log("MySQL Error: " . $db->error);
        error_log("MySQL Error No: " . $db->errno);
    }
    
    $error_message = urlencode($e->getMessage());
    header("Location: ../createjob.php?error=job_creation_failed&message=" . $error_message);
    exit();
}
?> 