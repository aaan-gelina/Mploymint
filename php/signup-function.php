<?php
session_start();
include '../dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = trim($_POST["name"]);
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);
  $type = trim($_POST["type"]);

  $query = "SELECT email FROM user WHERE email = ?";
  $statement = $db->prepare($query);
  $statement->bind_param("s", $email);
  $statement->execute();
  $statement->store_result();

  if ($statement->num_rows > 0) {
    header("Location: ../signup.php?error=email_taken");
    exit();
  }

  echo "<pre>";
  var_dump($_FILES["profileimg"]);
  echo "</pre>";
  exit();

  $profileimg = null;
  if (isset($_FILES["profileimg"]) && $_FILES["profileimg"]["error"] === 0) {
    $upload_dir = realpath(__DIR__ . '/../img/') . '/';
    $img_name = basename($_FILES["profileimg"]["name"]);
    $unique_name = time() . "_" . $img_name;
    $target_path = $upload_dir . $unique_name;
    if (move_uploaded_file($_FILES["profileimg"]["tmp_name"], $target_path)) {
      $profileimg = $unique_name;
    }
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
