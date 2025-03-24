<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/dbconnect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["resume"])) {
    $uid = $_SESSION['uid'];
    $file = $_FILES["resume"];
    $response = array();

    // Check for upload errors
    if ($file["error"] !== UPLOAD_ERR_OK) {
        header("Location: ../profile.php?error=upload");
        exit();
    }

    // Validate file type
    $allowed_types = array('application/pdf', 'application/msword');
    $file_type = mime_content_type($file["tmp_name"]);
    
    if (!in_array($file_type, $allowed_types)) {
        header("Location: ../profile.php?error=type");
        exit();
    }

    // Generate unique filename
    $extension = pathinfo($file["name"], PATHINFO_EXTENSION);
    $new_filename = uniqid("resume_") . "." . $extension;
    $upload_path = $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/uploads/resumes/';

    // Create directory if it doesn't exist
    if (!file_exists($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    // Move uploaded file
    if (move_uploaded_file($file["tmp_name"], $upload_path . $new_filename)) {
        // Archive old resume if exists
        $archive_query = "UPDATE resume SET archive = 1 WHERE uid = ? AND archive = 0";
        $stmt = $db->prepare($archive_query);
        $stmt->bind_param("i", $uid);
        $stmt->execute();
        
        // Insert new resume record
        $insert_query = "INSERT INTO resume (uid, filename, archive) VALUES (?, ?, 0)";
        $stmt = $db->prepare($insert_query);
        $stmt->bind_param("is", $uid, $new_filename);
        
        if ($stmt->execute()) {
            header("Location: ../profile.php?success=resume");
            exit();
        } else {
            // If database insert fails, delete uploaded file
            unlink($upload_path . $new_filename);
            header("Location: ../profile.php?error=database");
            exit();
        }
    } else {
        header("Location: ../profile.php?error=move");
        exit();
    }
} else {
    header("Location: ../profile.php?error=invalid");
    exit();
}
?> 