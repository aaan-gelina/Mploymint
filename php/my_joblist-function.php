<?php
session_start();

include 'dbconnect.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$uid = $_SESSION["uid"];

$query = "SELECT name, email FROM user WHERE uid = ?";
$statement = $db->prepare($query);
$statement->bind_param("i", $uid);
$statement->execute();
$result = $statement->get_result();
$user = $result->fetch_assoc();

if (!$user) {
  session_unset();
  session_destroy();
  header("Location: ../login.php");
  exit();
}

$user_name = $user['name'];
$user_email = $user['email'];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["cancel_jid"])) {
  $jid = intval($_POST["cancel_jid"]);

  $prev_query = $db->prepare("SELECT * FROM application WHERE uid = ? AND jid = ?");
  $prev_query->bind_param("ii", $uid, $jid);
  $prev_query->execute();
  $prev_result = $prev_query->get_result();
  $prev_data = $prev_result->fetch_assoc();
  $prev_value = json_encode($prev_data);
  $prev_query->close();

  $cancel_stmt = $db->prepare("DELETE FROM application WHERE uid = ? AND jid = ?");
  $cancel_stmt->bind_param("ii", $uid, $jid);
  
  if ($cancel_stmt->execute()) {
    $log_stmt = $db->prepare("
      INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value)
      VALUES (?, ?, 'Delete', 'User canceled a job application', 'application', 'DELETE', ?, '')
    ");
    $log_stmt->bind_param("iss", $uid, $user_email, $prev_value);
    $log_stmt->execute();
    $log_stmt->close();
  }

  $cancel_stmt->close();
  header("Location: ../my_joblist.php");
  exit();
}

$jobs = [];
$job_query = "
SELECT j.*, a.status, u.name AS company_name
FROM application a
LEFT JOIN job j ON j.jid = a.jid
LEFT JOIN user u ON j.cid = u.uid AND u.type = 'company'
WHERE a.uid = ?
";

$job_stmt = $db->prepare($job_query);
$job_stmt->bind_param("i", $uid);
$job_stmt->execute();
$job_result = $job_stmt->get_result();

if ($job_result && $job_result->num_rows > 0) {
  while ($row = $job_result->fetch_assoc()) {
    $jobs[] = $row;
  }
}
$job_stmt->close();
?>
