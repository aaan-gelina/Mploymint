<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/dbconnect.php';


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}


$user_data = [];
if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
    

    $query = "SELECT name, email, description, phone, location, skills 
             FROM user 
             WHERE uid = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("i", $uid);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        

        $_SESSION['name'] = $user_data['name'];
        $_SESSION['email'] = $user_data['email'];
        $_SESSION['bio'] = $user_data['description']; // Store description as bio
        $_SESSION['phone'] = $user_data['phone'];
        $_SESSION['location'] = $user_data['location'];
        $_SESSION['skills'] = $user_data['skills'];
    }
    $stmt->close();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = $_POST['phone'] ?? '';
    $location = $_POST['location'] ?? '';
    $bio = $_POST['bio'] ?? ''; 
    $skills = $_POST['skills'] ?? '';
    

    $update_query = "UPDATE user SET phone = ?, location = ?, description = ?, skills = ? WHERE uid = ?";
    $stmt = $db->prepare($update_query);
    $stmt->bind_param("ssssi", $phone, $location, $bio, $skills, $uid);
    
    if ($stmt->execute()) {
       
        $_SESSION['phone'] = $phone;
        $_SESSION['location'] = $location;
        $_SESSION['bio'] = $bio; 
        $_SESSION['skills'] = $skills;
        
        header("Location: ../profile.php?success=1");
        exit();
    } else {
        header("Location: ../profile.php?error=1");
        exit();
    }
    $stmt->close();
}


?> 