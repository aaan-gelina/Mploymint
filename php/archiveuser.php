<?php
session_start();
require '../dbconnect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['uid'])) {
    echo json_encode(["message" => "You must log in to archive a record."]);
    exit;
}

$uid = $_SESSION['uid'];
$userid = isset($_POST['uid']) ? intval($_POST['uid']) : 0;

if ($userid === 0) {
    echo json_encode(["message" => "Invalid user ID."]);
    exit;
}

// Fetch email from user table
$emailQuery = $db->prepare("SELECT email FROM user WHERE uid = ?");
$emailQuery->bind_param("i", $uid);
$emailQuery->execute();
$emailQuery->bind_result($email);
$emailQuery->fetch();
$emailQuery->close();

// Fetch previous record for audit
$prevStmt = $db->prepare("SELECT * FROM user WHERE uid = ?");
$prevStmt->bind_param("i", $userid);
$prevStmt->execute();
$result = $prevStmt->get_result();
$prev_row = $result->fetch_assoc();
$prev_value = json_encode($prev_row);
$prevStmt->close();

// Archive the record
$stmt = $db->prepare("UPDATE user SET archive = 1 WHERE uid = ?");
$stmt->bind_param("i", $userid);
if ($stmt->execute()) {
    $auditStmt = $db->prepare("
        INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value)
        VALUES (?, ?, 'Delete', 'Record archived by admin', 'user', 'UPDATE', ?, '')
    ");
    $auditStmt->bind_param("sss", $uid, $email, $prev_value);
    $auditStmt->execute();
    $auditStmt->close();

    echo json_encode(["message" => "User archived successfully."]);
} else {
    echo json_encode(["message" => "Error archiving user."]);
}
$stmt->close();
?>
