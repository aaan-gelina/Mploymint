<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/dbconnect.php';

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
    $statement->execute();
    $statement->close();
    header("Location: ../discussion.php");
    exit();
  }

  if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["delete_post_id"])) {
    $post_id = intval($_POST["delete_post_id"]);

    $delete_statement = $db->prepare("DELETE FROM discussion WHERE did = ?");
    $delete_statement->bind_param("i", $post_id);
    $delete_statement->execute();
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
?>
