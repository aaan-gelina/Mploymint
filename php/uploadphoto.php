<?php
    session_start();
    include '../dbconnect.php';

    header('Content-Type: application/json');

    if (!isset($_SESSION['uid'])) {
        echo json_encode(["success" => false, "message" => "You must log in to update your photo."]);
        exit;
    }

    $uid = $_SESSION['uid'];
    $uploadDir = $_SERVER['DOCUMENT_ROOT'] . "/Mploymint/img/";
    $allowedExtensions = ["jpg", "jpeg", "png", "gif"];

    //Fetch profile image path from database
    $fetchStmt = $db->prepare("SELECT profileimg FROM user WHERE uid = ?");
    $fetchStmt->bind_param("i", $uid);
    $fetchStmt->execute();
    $fetchStmt->bind_result($existingProfileImg);
    $fetchStmt->fetch();
    $fetchStmt->close();

    //Validate file
    if (!isset($_FILES["profileimg"]) || $_FILES["profileimg"]["error"] !== UPLOAD_ERR_OK) {
        echo json_encode(["success" => false, "message" => "Error uploading file."]);
        exit;
    }

    //Get file extension
    $fileTmpPath = $_FILES["profileimg"]["tmp_name"];
    $fileName = $_FILES["profileimg"]["name"];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($fileExt, $allowedExtensions)) {
        echo json_encode(["success" => false, "message" => "Invalid file type. Only JPG, PNG, and GIF allowed."]);
        exit;
    }

    //Check for duplicates, delete if exist
    $newFileName = "profile_" . $uid . "." . $fileExt;
    $destPath = $uploadDir . $newFileName;
    $dbPath = "/Mploymint/img/" . $newFileName;

    if (!empty($existingProfileImg) && file_exists($_SERVER['DOCUMENT_ROOT'] . $existingProfileImg) && $existingProfileImg !== "/Mploymint/img/default.jpg") {
        unlink($_SERVER['DOCUMENT_ROOT'] . $existingProfileImg);
    }

    //Place file
    if (!move_uploaded_file($fileTmpPath, $destPath)) {
        echo json_encode(["success" => false, "message" => "Error moving uploaded file."]);
        exit;
    }

    //Update image path in database
    $updateStmt = $db->prepare("UPDATE user SET profileimg = ? WHERE uid = ?");
    $updateStmt->bind_param("si", $dbPath, $uid);

    if ($updateStmt->execute()) {
        //Create audit
        $auditStmt = $db->prepare("
            INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value) 
            VALUES (?, ?, 'Update', 'User updated profile photo', 'user', 'UPDATE', ?, ?)
        ");
        $new_value = json_encode(["profileimg" => $dbPath]);
        $auditStmt->bind_param("ssss", $uid, $email, $prev_value, $new_value);
        $auditStmt->execute();
        $auditStmt->close();

        echo json_encode(["success" => true, "message" => "Profile photo updated!", "newPath" => $dbPath]);
    } else {
        echo json_encode(["success" => false, "message" => "Database update failed."]);
    }

    $updateStmt->close();
?>

