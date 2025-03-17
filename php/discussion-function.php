<?php
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
?>
