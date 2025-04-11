<?php
session_start();
require '../dbconnect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['uid'])) {
    echo json_encode(["message" => "You must log in to archive a record."]);
    exit;
}

$uid = $_SESSION['uid'];
$did = isset($_POST['did']) ? intval($_POST['did']) : 0;

if ($did === 0) {
    echo json_encode(["message" => "Invalid discussion ID."]);
    exit;
}

$emailQuery = $db->prepare("SELECT email FROM user WHERE uid = ?");
$emailQuery->bind_param("i", $uid);
$emailQuery->execute();
$emailQuery->bind_result($email);
$emailQuery->fetch();
$emailQuery->close();

$prevStmt = $db->prepare("SELECT * FROM discussion WHERE did = ?");
$prevStmt->bind_param("i", $did);
$prevStmt->execute();
$result = $prevStmt->get_result();
$prev_row = $result->fetch_assoc();
$prev_value = json_encode($prev_row);
$prevStmt->close();

$stmt = $db->prepare("UPDATE discussion SET archive = 1 WHERE did = ?");
$stmt->bind_param("i", $did);
if ($stmt->execute()) {
    $auditStmt = $db->prepare("
        INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value)
        VALUES (?, ?, 'Delete', 'Record archived by admin', 'discussion', 'UPDATE', ?, '')
    ");
    $auditStmt->bind_param("sss", $uid, $email, $prev_value);
    $auditStmt->execute();
    $auditStmt->close();

    echo json_encode(["message" => "Discussion archived successfully."]);
} else {
    echo json_encode(["message" => "Error archiving discussion."]);
}
$stmt->close();
?>
