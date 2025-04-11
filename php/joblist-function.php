<?php
session_start();
include 'dbconnect.php';

$uid = $_SESSION["uid"];

$query = "SELECT name, email, type FROM user WHERE uid = ?";
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
$user_type = $user['type'];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  if (isset($_POST["apply_jid"])) {
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

  if (isset($_POST["delete_jid"]) && $user_type === "company") {
    $jid = intval($_POST["delete_jid"]);

    $check_stmt = $db->prepare("SELECT * FROM job WHERE jid = ? AND cid = ?");
    $check_stmt->bind_param("ii", $jid, $uid);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();
    $job_data = $check_result->fetch_assoc();
    $check_stmt->close();

    if ($job_data) {
      $prev_value = json_encode($job_data);

      $delete_stmt = $db->prepare("UPDATE job SET status = 'archived', archive = 1 WHERE jid = ? AND cid = ?");
      $delete_stmt->bind_param("ii", $jid, $uid);
      if ($delete_stmt->execute()) {
        $audit_stmt = $db->prepare("
          INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value)
          VALUES (?, ?, 'Delete', 'Company deleted a job', 'job', 'ARCHIVE', ?, '')
        ");
        $audit_stmt->bind_param("iss", $uid, $user_email, $prev_value);
        $audit_stmt->execute();
        $audit_stmt->close();
      }
      $delete_stmt->close();
    }

    header("Location: ../joblist.php?type=company");
    exit();
  }
}

$applied_jobs = [];
if ($user_type === 'jobseeker') {
  $applied_query = $db->prepare("SELECT jid FROM application WHERE uid = ?");
  $applied_query->bind_param("i", $uid);
  $applied_query->execute();
  $applied_result = $applied_query->get_result();

  while ($row = $applied_result->fetch_assoc()) {
    $applied_jobs[] = $row['jid'];
  }
  $applied_query->close();
}

$jobs = [];

if ($user_type === 'company') {
  $job_query = $db->prepare("SELECT jid, title, location, type, salary, appdeadline, status FROM job WHERE archive = 0 AND cid = ? ORDER BY jid DESC");
  $job_query->bind_param("i", $uid);
  $job_query->execute();
  $result = $job_query->get_result();
} else {
  $result = $db->query("SELECT jid, title, location, type, salary FROM job WHERE status = 'active' AND archive = 0 ORDER BY jid DESC");
}

if ($result && $result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
    $jobs[] = $row;
  }
}
?>
