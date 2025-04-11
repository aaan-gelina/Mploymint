<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

include __DIR__ . '/../dbconnect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit();
}

$user_data = [];
if (isset($_SESSION['uid'])) {
    $uid = $_SESSION['uid'];
    
    $query = "SELECT name, email, description, phone, location, skills 
             FROM user 
             WHERE uid = ?";
    $stmt = $db->prepare($query);
    if (!$stmt) {
        error_log("Prepare failed in profile select: " . $db->error);
        header("Location: ../profile.php?error=database");
        exit();
    }
    
    $stmt->bind_param("i", $uid);
    if (!$stmt->execute()) {
        error_log("Execute failed in profile select: " . $stmt->error);
        header("Location: ../profile.php?error=database");
        exit();
    }
    
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        
        $_SESSION['name'] = $user_data['name'];
        $_SESSION['email'] = $user_data['email'];
        
        if ($_SESSION['type'] === 'company') {
            $_SESSION['description'] = $user_data['description']; 
        } else {
            $_SESSION['bio'] = $user_data['description']; 
        }
        
        $_SESSION['phone'] = $user_data['phone'];
        $_SESSION['location'] = $user_data['location'];
        $_SESSION['skills'] = $user_data['skills'];
    }
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['uid'])) {
        error_log("User ID not set in session for profile update");
        header("Location: ../profile.php?error=session");
        exit();
    }
    
    $uid = $_SESSION['uid'];
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $location = isset($_POST['location']) ? $_POST['location'] : '';
    $skills = isset($_POST['skills']) ? $_POST['skills'] : '';
    
    if ($_SESSION['type'] === 'company') {
        $description = isset($_POST['description']) ? $_POST['description'] : '';
    } else {
        $description = isset($_POST['bio']) ? $_POST['bio'] : ''; 
    }
    
    // Log what we're trying to update
    error_log("Updating profile for UID: $uid - Phone: $phone, Location: $location, Skills: $skills, Description length: " . strlen($description));

    $update_query = "UPDATE user SET phone = ?, location = ?, description = ?, skills = ? WHERE uid = ?";
    $stmt = $db->prepare($update_query);
    
    if (!$stmt) {
        error_log("Prepare failed in profile update: " . $db->error);
        header("Location: ../profile.php?error=database");
        exit();
    }
    
    $stmt->bind_param("ssssi", $phone, $location, $description, $skills, $uid);
    
    if ($stmt->execute()) {
        $_SESSION['phone'] = $phone;
        $_SESSION['location'] = $location;
        
        if ($_SESSION['type'] === 'company') {
            $_SESSION['description'] = $description;
        } else {
            $_SESSION['bio'] = $description;
        }
        
        $_SESSION['skills'] = $skills;
        
        error_log("Profile updated successfully for UID: $uid");
        header("Location: ../profile.php?success=1");
        exit();
    } else {
        error_log("Execute failed in profile update: " . $stmt->error);
        header("Location: ../profile.php?error=database");
        exit();
    }
    $stmt->close();
}
?> 