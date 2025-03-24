<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/dbconnect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['uid'])) {
    echo json_encode(["message" => "You must log in to apply for a job."]);
    exit;
}

$uid = $_SESSION['uid'];
$jid = isset($_POST['jid']) ? intval($_POST['jid']) : 0;
$cid = isset($_POST['cid']) ? intval($_POST['cid']) : 0;

if ($jid === 0 || $cid === 0) {
    echo json_encode(["message" => "Invalid job or company ID."]);
    exit;
}

//Fetch email from database
$emailQuery = $db->prepare("SELECT email FROM user WHERE uid = ?");
$emailQuery->bind_param("i", $uid);
$emailQuery->execute();
$emailQuery->bind_result($email);
$emailQuery->fetch();
$emailQuery->close();

//Check for existing application
$checkStmt = $db->prepare("SELECT aid FROM application WHERE uid = ? AND jid = ?");
$checkStmt->bind_param("ii", $uid, $jid);
$checkStmt->execute();
$checkStmt->store_result();

if ($checkStmt->num_rows > 0) {
    echo json_encode(["message" => "You have already applied for this job."]);
} else {
    //Create application
    $insertStmt = $db->prepare("INSERT INTO application (jid, uid, cid, status) VALUES (?, ?, ?, 'pending')");
    $insertStmt->bind_param("iii", $jid, $uid, $cid);
    
    if ($insertStmt->execute()) {
        //Log new values via json
        $new_value = json_encode(["jid" => $jid, "uid" => $uid, "cid" => $cid, "status" => "pending"]);

        //Create audit
        $auditStmt = $db->prepare("
            INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value) 
            VALUES (?, ?, 'Create', 'User applied for job', 'application', 'INSERT', '', ?)
        ");
        $auditStmt->bind_param("sss", $uid, $email, $new_value);
        $auditStmt->execute();
        $auditStmt->close();

        echo json_encode(["message" => "Successfully applied for the job."]);
    } else {
        echo json_encode(["message" => "Error applying for the job. Please try again."]);
    }

    $insertStmt->close();
}

$checkStmt->close();
?>


