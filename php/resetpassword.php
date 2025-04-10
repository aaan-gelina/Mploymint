<?php
    session_start();
    require '../dbconnect.php';

    header('Content-Type: application/json');

    if (!isset($_SESSION['uid'])) {
        echo json_encode(["success" => false, "message" => "You must log in to reset your password."]);
        exit;
    }

    $uid = $_SESSION['uid'];
    $currPass = trim($_POST['pass'] ?? '');
    $newPass = trim($_POST['newpass'] ?? '');
    $confirmPass = trim($_POST['confirmpass'] ?? '');

    if (empty($currPass) || empty($newPass) || empty($confirmPass)) {
        echo json_encode(["success" => false, "message" => "All fields are required."]);
        exit;
    }

    if ($newPass !== $confirmPass) {
        echo json_encode(["success" => false, "message" => "New passwords do not match."]);
        exit;
    }

    //Fetch current password
    $fetchStmt = $db->prepare("SELECT password, email FROM user WHERE uid = ?");
    $fetchStmt->bind_param("i", $uid);
    $fetchStmt->execute();
    $fetchStmt->bind_result($storedPassword, $email);
    $fetchStmt->fetch();
    $fetchStmt->close();

    if ($currPass !== $storedPassword) {
        echo json_encode(["success" => false, "message" => "Current password is incorrect."]);
        exit;
    }

    $prev_value = json_encode(["password" => $storedPassword]);

    //Update password in database
    $updateStmt = $db->prepare("UPDATE user SET password = ? WHERE uid = ?");
    $updateStmt->bind_param("si", $newPass, $uid);

    if ($updateStmt->execute()) {
        //Create audit
        $auditStmt = $db->prepare("
            INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value) 
            VALUES (?, ?, 'Update', 'User reset password', 'user', 'UPDATE', ?, ?)
        ");
        $new_value = json_encode(["password" => $newPass]);
        $auditStmt->bind_param("ssss", $uid, $email, $prev_value, $new_value);
        $auditStmt->execute();
        $auditStmt->close();

        echo json_encode(["success" => true, "message" => "Password reset successfully!"]);
    } else {
        echo json_encode(["success" => false, "message" => "Error resetting password. Please try again."]);
    }

    $updateStmt->close();
?>
