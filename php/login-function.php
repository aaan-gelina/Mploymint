<?php
session_start();
include '../dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  $safe_email = mysqli_real_escape_string($db, $email);

  $query = "SELECT uid, password, name FROM user WHERE email = ?";
  $stmt = $db->prepare($query);
  $stmt->bind_param("s", $safe_email);
  $stmt->execute();
  $result = $stmt->get_result();
    
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
      
    if ($password == $user["password"]) {
      $_SESSION["loggedin"] = true;
      $_SESSION["uid"] = $row["uid"];
      $_SESSION["name"] = $row["name"];

      header("Location: ../discussion.php");
      exit();
    } else {
      header("Location: ../login.php?error=invalid_password");
      exit();
    }
  } else {
    header("Location: ../login.php?error=no_account");
    exit();
  }
}
?>
