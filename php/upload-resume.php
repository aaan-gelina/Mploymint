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

// Create upload directory if it doesn't exist
if (!is_dir($uploads_dir)) {
    error_log("Attempting to create uploads directory: " . $uploads_dir);
    if (!mkdir($uploads_dir, 0755, true)) {
        error_log("Failed to create uploads directory: " . $uploads_dir . ". Error: " . error_get_last()['message']);
        header("Location: ../profile.php?error=dir_create");
        exit();
    }
}

// Create resumes directory if it doesn't exist
if (!is_dir($resumes_dir)) {
    error_log("Attempting to create resumes directory: " . $resumes_dir);
    if (!mkdir($resumes_dir, 0755, true)) {
        error_log("Failed to create resumes directory: " . $resumes_dir . ". Error: " . error_get_last()['message']);
        header("Location: ../profile.php?error=dir_create");
        exit();
    }
}

error_log("Using directory for resume upload: " . $resumes_dir);

$destination_path = $resumes_dir . '/' . $new_filename;

if (!move_uploaded_file($file_tmp_path, $destination_path)) {
    $move_error = error_get_last();
    error_log("Failed to move uploaded file via move_uploaded_file: " . ($move_error['message'] ?? 'Unknown error'));
    
    if (copy($file_tmp_path, $destination_path)) {
        unlink($file_tmp_path); 
        error_log("Successfully copied file using copy() instead");
    } else {
        $copy_error = error_get_last();
        error_log("Both move_uploaded_file and copy failed for resume: " . $original_filename . 
                  " to " . $destination_path . ". Error: " . ($copy_error['message'] ?? 'Unknown error'));
        
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

    // Insert new resume record
    $insert_query = "INSERT INTO resume (uid, filename, archive) VALUES (?, ?, 0)";
    $stmt_insert = $db->prepare($insert_query);
    if (!$stmt_insert) throw new Exception("Prepare failed (insert): " . $db->error);
   
    $stmt_insert->bind_param("is", $uid, $new_filename); 
    if (!$stmt_insert->execute()) throw new Exception("Execute failed (insert): " . $stmt_insert->error);
    $new_resume_id = $stmt_insert->insert_id; 
    $stmt_insert->close();

    $db->commit(); 
    
    error_log("Resume uploaded successfully for user ID: " . $uid . ". File: " . $new_filename . " saved to " . $destination_path);
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