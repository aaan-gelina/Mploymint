<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once dirname(__FILE__) . '/../dbconnect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['uid'])) {
    header("Location: ../login.php?error=session_expired");
    exit();
}
$uid = $_SESSION['uid'];
$email = $_SESSION['email'] ?? 'unknown'; 

if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_FILES["resume"])) {
    error_log("Invalid request method or missing file for resume upload. User ID: " . $uid);
    header("Location: ../profile.php?error=invalid");
    exit();
}
$file = $_FILES["resume"];

if ($file["error"] !== UPLOAD_ERR_OK) {
    $upload_errors = [
        UPLOAD_ERR_INI_SIZE   => "File exceeds upload_max_filesize directive in php.ini.",
        UPLOAD_ERR_FORM_SIZE  => "File exceeds MAX_FILE_SIZE directive specified in the HTML form.",
        UPLOAD_ERR_PARTIAL    => "File was only partially uploaded.",
        UPLOAD_ERR_NO_FILE    => "No file was uploaded.",
        UPLOAD_ERR_NO_TMP_DIR => "Missing temporary folder.",
        UPLOAD_ERR_CANT_WRITE => "Failed to write file to disk.",
        UPLOAD_ERR_EXTENSION  => "A PHP extension stopped the file upload.",
    ];
    $error_message = $upload_errors[$file["error"]] ?? "Unknown upload error.";
    error_log("Resume upload error for User ID: " . $uid . " - Error Code: " . $file["error"] . " (" . $error_message . ")");
    header("Location: ../profile.php?error=upload");
    exit();
}

$allowed_mime_types = [
    'application/pdf'
];
$file_tmp_path = $file["tmp_name"];
$file_mime_type = mime_content_type($file_tmp_path);

if (!in_array($file_mime_type, $allowed_mime_types)) {
    error_log("Invalid resume file type uploaded: " . $file_mime_type . " for User ID: " . $uid);
    header("Location: ../profile.php?error=type");
    exit();
}

$original_filename = $file["name"];
$extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
$new_filename = "resume_" . $uid . "_" . uniqid() . "." . $extension;

if ($extension !== 'pdf') {
    error_log("Invalid resume file extension uploaded: " . $extension . " for User ID: " . $uid);
    header("Location: ../profile.php?error=type");
    exit();
}

// Use relative paths with dirname() to navigate from current directory
$base_dir = dirname(__FILE__) . '/..';
$uploads_dir = $base_dir . '/uploads';
$resumes_dir = $uploads_dir . '/resumes';

// Check if directories exist and are writable
if (!is_dir($resumes_dir)) {
    error_log("Resumes directory does not exist: " . $resumes_dir);
    header("Location: ../profile.php?error=dir_missing");
    exit();
}

if (!is_writable($resumes_dir)) {
    error_log("Resumes directory is not writable: " . $resumes_dir);
    
    // Try to create a user-specific directory
    $user_resumes_dir = $uploads_dir . '/user_' . $uid;
    
    if (!is_dir($user_resumes_dir)) {
        if (!mkdir($user_resumes_dir, 0755, true)) {
            error_log("Failed to create user-specific resumes directory: " . $user_resumes_dir);
            header("Location: ../profile.php?error=dir_permissions");
            exit();
        }
    }
    
    if (!is_writable($user_resumes_dir)) {
        error_log("User-specific directory is also not writable: " . $user_resumes_dir);
        header("Location: ../profile.php?error=dir_permissions");
        exit();
    }
    
    // Use the user-specific directory instead
    $resumes_dir = $user_resumes_dir;
}

error_log("Using directory for resume upload: " . $resumes_dir);

$destination_path = $resumes_dir . '/' . $new_filename;

if (!move_uploaded_file($file_tmp_path, $destination_path)) {
    $move_error = error_get_last();
    error_log("Failed to move uploaded file via move_uploaded_file: " . ($move_error['message'] ?? 'Unknown error'));
    
    // Try copy as a fallback
    if (copy($file_tmp_path, $destination_path)) {
        unlink($file_tmp_path); 
        error_log("Successfully copied file using copy() instead");
    } else {
        $copy_error = error_get_last();
        error_log("Both move_uploaded_file and copy failed for resume: " . $original_filename . 
                  " to " . $destination_path . ". Error: " . ($copy_error['message'] ?? 'Unknown error'));
        
        // Get directory permissions for debugging
        $perms = substr(sprintf('%o', fileperms($resumes_dir)), -4);
        error_log("Directory permissions: " . $perms);
        
        header("Location: ../profile.php?error=move");
        exit();
    }
}

$db->begin_transaction(); 

try {
    // Archive existing resumes for this user
    $archive_query = "UPDATE resume SET archive = 1 WHERE uid = ? AND archive = 0";
    $stmt_archive = $db->prepare($archive_query);
    if (!$stmt_archive) throw new Exception("Prepare failed (archive): " . $db->error);
    $stmt_archive->bind_param("i", $uid);
    if (!$stmt_archive->execute()) throw new Exception("Execute failed (archive): " . $stmt_archive->error);
    $stmt_archive->close();

    // Store just the filename, not the full path
    $stored_filename = basename($destination_path);
    
    // Insert new resume record
    $insert_query = "INSERT INTO resume (uid, filename, archive) VALUES (?, ?, 0)";
    $stmt_insert = $db->prepare($insert_query);
    if (!$stmt_insert) throw new Exception("Prepare failed (insert): " . $db->error);
   
    $stmt_insert->bind_param("is", $uid, $stored_filename); 
    if (!$stmt_insert->execute()) throw new Exception("Execute failed (insert): " . $stmt_insert->error);
    $new_resume_id = $stmt_insert->insert_id; 
    $stmt_insert->close();

    $db->commit(); 
    
    error_log("Resume uploaded successfully for user ID: " . $uid . ". File: " . $stored_filename . " saved to " . $destination_path);
    header("Location: ../profile.php?success=resume");
    exit();

} catch (Exception $e) {
    $db->rollback(); 
    error_log("Database error during resume update for User ID: " . $uid . ". Error: " . $e->getMessage());
    
    if (file_exists($destination_path)) {
        unlink($destination_path);
    }
    
    header("Location: ../profile.php?error=database");
    exit();
}
?> 