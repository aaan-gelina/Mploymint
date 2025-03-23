<?php
session_start();
include '../dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $email = trim($_POST["email"]);
  $password = trim($_POST["password"]);

  $safe_email = mysqli_real_escape_string($db, $email);

  $query = "SELECT uid, password, name FROM user WHERE email = ?";
  $statement = $db->prepare($query);
  $statement->bind_param("s", $safe_email);
  $statement->execute();
  $result = $statement->get_result();
    
  if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
      
    if ($password == $row["password"]) {
      $_SESSION["loggedin"] = true;
      $_SESSION["uid"] = $row["uid"];
      $_SESSION["name"] = $row["name"];
      $_SESSION["email"] = $safe_email;
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
