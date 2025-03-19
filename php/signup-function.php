<?php
session_start();
include '../dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST["name"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $type = trim($_POST["type"]);
  $profileimg = "profile.jpg";

  $query = "SELECT email FROM user WHERE email = ?";
  $statement = $db->prepare($query);
  $statement->bind_param("s", $email);
  $statement->execute();
  $statement->store_result();

  if ($statement->num_rows > 0) {
    header("Location: ../signup.php?error=email_taken");
    exit();
  }

  $query = "INSERT INTO user (name, email, password, type, profileimg) VALUES (?, ?, ?, ?, ?)";
  $statement = $db->prepare($query);
  $statement->bind_param("sssss", $name, $email, $password, $type, $profileimg);
    
  if ($statement->execute()) {
    header("Location: ../login.php");
    exit();
  } else {
    header("Location: ../signup.php?error=signup_failed");
    exit();
  }
}
?>
