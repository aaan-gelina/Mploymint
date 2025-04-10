<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/dbconnect.php';

// 1. Check Login Status
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['uid'])) {
    // Redirect to login if not logged in or UID is missing
    header("Location: ../login.php?error=session_expired");
    exit();
}
$uid = $_SESSION['uid'];
$email = $_SESSION['email'] ?? 'unknown'; // Get email for audit log, default if not set

// 2. Validate Request Method and File Upload
if ($_SERVER["REQUEST_METHOD"] != "POST" || !isset($_FILES["resume"])) {
    error_log("Invalid request method or missing file for resume upload. User ID: " . $uid);
    header("Location: ../profile.php?error=invalid");
    exit();
}
$file = $_FILES["resume"];

// 3. Check for Upload Errors (e.g., file size exceeded)
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

// 4. Validate File Type (using MIME type)
$allowed_mime_types = [
    'application/pdf',                                                     // .pdf
    'application/msword',                                                  // .doc
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document' // .docx
];
$file_tmp_path = $file["tmp_name"];
$file_mime_type = mime_content_type($file_tmp_path);

if (!in_array($file_mime_type, $allowed_mime_types)) {
    error_log("Invalid resume file type uploaded: " . $file_mime_type . " for User ID: " . $uid);
    header("Location: ../profile.php?error=type");
    exit();
}

// 5. Prepare File Paths and New Filename
$original_filename = $file["name"];
$extension = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
$new_filename = "resume_" . $uid . "_" . uniqid() . "." . $extension; // Include UID for easier tracking
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/uploads/resumes/'; // Use absolute path
$destination_path = $upload_dir . $new_filename;
// $db_path = "/Mploymint/uploads/resumes/" . $new_filename; // Path to store in DB (relative to web root) - Decided to store filename only

// 6. Ensure Upload Directory Exists
if (!is_dir($upload_dir)) {
    // Attempt to create directory (requires parent dir permissions)
    // Use 0755 for permissions (rwxr-xr-x) - slightly safer than 0777
    if (!mkdir($upload_dir, 0755, true)) { 
        error_log("Failed to create upload directory: " . $upload_dir . ". Check parent directory permissions. User ID: " . $uid);
        header("Location: ../profile.php?error=dir_create");
        exit();
    }
}

// 7. Move Uploaded File
if (!move_uploaded_file($file_tmp_path, $destination_path)) {
    $last_error = error_get_last();
    error_log("Failed to move uploaded resume: " . $original_filename . " to " . $destination_path . ". Error: " . ($last_error['message'] ?? 'Unknown error') . ". User ID: " . $uid);
    header("Location: ../profile.php?error=move");
    exit();
}

// 8. Database Operations (Archive old, Insert new)
$db->begin_transaction(); // Start transaction

try {
    // Archive old resumes
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
    // Store just the filename
    $stmt_insert->bind_param("is", $uid, $new_filename); 
    if (!$stmt_insert->execute()) throw new Exception("Execute failed (insert): " . $stmt_insert->error);
    $new_resume_id = $stmt_insert->insert_id; // Get the ID of the newly inserted resume
    $stmt_insert->close();
    
    /* // Temporarily Commented Out Audit Log
    if ($email !== 'unknown') { // Only log if email is known
        $audit_desc = "User uploaded new resume (ID: " . $new_resume_id . ", File: " . $new_filename . ")";
        $audit_table = "resume";
        $audit_operation = "INSERT";
        $audit_prev = "N/A";
        $audit_new = json_encode(['filename' => $new_filename, 'resume_id' => $new_resume_id]);
        
        $audit_query = "INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value) 
                        VALUES (?, ?, 'Upload', ?, ?, ?, ?, ?)";
        $stmt_audit = $db->prepare($audit_query);
        if ($stmt_audit) {
            $stmt_audit->bind_param("issssss", $uid, $email, $audit_desc, $audit_table, $audit_operation, $audit_prev, $audit_new);
            if (!$stmt_audit->execute()) {
                // Log audit failure but don't rollback the main transaction for this
                error_log("Failed to insert audit log for resume upload. User ID: " . $uid . ". Error: " . $stmt_audit->error);
            }
            $stmt_audit->close();
        } else {
            error_log("Failed to prepare audit log statement for resume upload. User ID: " . $uid . ". Error: " . $db->error);
        }
    }
    */

    // If all DB operations succeeded (without audit log for now)
    $db->commit(); // Commit transaction
    header("Location: ../profile.php?success=resume");
    exit();

} catch (Exception $e) {
    $db->rollback(); // Rollback transaction on error
    error_log("Database error during resume update for User ID: " . $uid . ". Error: " . $e->getMessage());
    
    // Clean up the uploaded file if DB operations failed
    if (file_exists($destination_path)) {
        unlink($destination_path);
    }
    
    header("Location: ../profile.php?error=database");
    exit();
}

?> 