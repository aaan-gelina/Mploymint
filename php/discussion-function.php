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

$user_name = htmlspecialchars($user['name']);
$user_email = htmlspecialchars($user['email']);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST["new-post-content"])) {
    $post_content = trim($_POST["new-post-content"]);

    $statement = $db->prepare("INSERT INTO discussion (creatorid, members, description, archive) VALUES (?, ?, ?, 0)");
    $default_members = "";
    $statement->bind_param("iss", $uid, $default_members, $post_content);
    
    if ($statement->execute()) {
      $new_value = json_encode(["creatorid" => $uid, "members" => "", "description" => $post_content, "archive" => 0]);

      $auditStmt = $db->prepare("
      INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value) 
      VALUES (?, ?, 'Create', 'User created a discussion post', 'discussion', 'INSERT', '', ?)
      ");
      $auditStmt->bind_param("iss", $uid, $user_email, $new_value);
      $auditStmt->execute();
      $auditStmt->close();
    }

    $statement->close();
    header("Location: ../discussion.php");
    exit();
  }

  if (isset($_POST["delete_post_id"])) {
    $post_id = intval($_POST["delete_post_id"]);

    $prevQuery = $db->prepare("SELECT * FROM discussion WHERE did = ?");
    $prevQuery->bind_param("i", $post_id);
    $prevQuery->execute();
    $prevResult = $prevQuery->get_result();
    $prevData = $prevResult->fetch_assoc();
    $prev_value = json_encode($prevData);
    $prevQuery->close();

    $delete_statement = $db->prepare("DELETE FROM discussion WHERE did = ?");
    $delete_statement->bind_param("i", $post_id);

    if ($delete_statement->execute()) {
      $auditStmt = $db->prepare("
      INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value) 
      VALUES (?, ?, 'Delete', 'User deleted a discussion post', 'discussion', 'DELETE', ?, '')
      ");
      $auditStmt->bind_param("sss", $uid, $user_email, $prev_value);
      $auditStmt->execute();
      $auditStmt->close();
    }

    $delete_statement->close();
    $db->close();

    header("Location: discussion.php");
    exit();
  }
}

$posts = [];
$post_query = "SELECT d.did, d.creatorid, u.name, d.description, d.archive FROM discussion d JOIN user u ON d.creatorid = u.uid ORDER BY d.did DESC";
$post_result = $db->query($post_query);

if ($post_result->num_rows > 0) {
  while ($row = $post_result->fetch_assoc()) {
    $posts[] = $row;
  }
}

