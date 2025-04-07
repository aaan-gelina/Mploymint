<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/dbconnect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
  header("Location: ../login.php");
  exit();
}

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

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["apply_jid"])) {
  $jid = intval($_POST["apply_jid"]);

  $cid_stmt = $db->prepare("SELECT cid FROM job WHERE jid = ?");
  $cid_stmt->bind_param("i", $jid);
  $cid_stmt->execute();
  $cid_result = $cid_stmt->get_result();
  $job_data = $cid_result->fetch_assoc();
  $cid_stmt->close();

  if ($job_data && isset($job_data['cid'])) {
    $cid = $job_data['cid'];

    $check = $db->prepare("SELECT * FROM application WHERE uid = ? AND jid = ?");
    $check->bind_param("ii", $uid, $jid);
    $check->execute();
    $check_result = $check->get_result();

    if ($check_result->num_rows === 0) {
      $apply_stmt = $db->prepare("INSERT INTO application (jid, uid, cid, status, archive) VALUES (?, ?, ?, 'pending', 0)");
      $apply_stmt->bind_param("iii", $jid, $uid, $cid);

      if ($apply_stmt->execute()) {
        $new_value = json_encode([
          "jid" => $jid,
          "uid" => $uid,
          "cid" => $cid,
          "status" => "pending",
          "archive" => 0
        ]);

        $audit_stmt = $db->prepare("
          INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value)
          VALUES (?, ?, 'Create', 'User applied for job', 'application', 'INSERT', '', ?)
        ");
        $audit_stmt->bind_param("iss", $uid, $user_email, $new_value);
        $audit_stmt->execute();
        $audit_stmt->close();
      }

      $apply_stmt->close();
    }

    $check->close();
  }

  header("Location: ../joblist.php");
  exit();
}

$applied_jobs = [];
$applied_query = $db->prepare("SELECT jid FROM application WHERE uid = ?");
$applied_query->bind_param("i", $uid);
$applied_query->execute();
$applied_result = $applied_query->get_result();

while ($row = $applied_result->fetch_assoc()) {
  $applied_jobs[] = $row['jid'];
}
$applied_query->close();

$jobs = [];
$job_query = "SELECT jid, title, location, type, salary FROM job WHERE status = 'active' AND archive = 0 ORDER BY jid DESC";
$job_result = $db->query($job_query);

if ($job_result && $job_result->num_rows > 0) {
  while ($row = $job_result->fetch_assoc()) {
    $jobs[] = $row;
  }
}
?>
