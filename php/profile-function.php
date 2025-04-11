<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log critical information for debugging
error_log("=== START PROFILE UPDATE ===");
error_log("Session data: " . json_encode($_SESSION));

try {
    include __DIR__ . '/../dbconnect.php';
    
    // Check DB connection
    if ($db->connect_error) {
        error_log("Database connection failed: " . $db->connect_error);
        header("Location: ../profile.php?error=db_connect");
        exit();
    }
    
    error_log("Database connection established successfully");
} catch (Exception $e) {
    error_log("Exception in database connection: " . $e->getMessage());
    header("Location: ../profile.php?error=db_include");
    exit();
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    error_log("User not logged in");
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST data received: " . json_encode($_POST));
    
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

    try {
        $update_query = "UPDATE user SET phone = ?, location = ?, description = ?, skills = ? WHERE uid = ?";
        $stmt = $db->prepare($update_query);
        
        if (!$stmt) {
            error_log("Prepare failed in profile update: " . $db->error);
            header("Location: ../profile.php?error=database_prepare");
            exit();
        }
        
        $bind_result = $stmt->bind_param("ssssi", $phone, $location, $description, $skills, $uid);
        if (!$bind_result) {
            error_log("Bind param failed: " . $stmt->error);
            header("Location: ../profile.php?error=database_bind");
            exit();
        }
        
        $execute_result = $stmt->execute();
        if (!$execute_result) {
            error_log("Execute failed in profile update: " . $stmt->error);
            header("Location: ../profile.php?error=database_execute");
            exit();
        }
        
        if ($stmt->affected_rows === 0) {
            error_log("No rows affected by update query");
            // Still proceed as this could just mean no changes were made
        }
        
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
    } catch (Exception $e) {
        error_log("Exception in profile update: " . $e->getMessage());
        header("Location: ../profile.php?error=exception");
        exit();
    }
} else {
    // Load user data for GET requests only
    $user_data = [];
    if (isset($_SESSION['uid'])) {
        $uid = $_SESSION['uid'];
        
        try {
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
                
                error_log("User data loaded successfully");
            } else {
                error_log("No user found with ID: $uid");
            }
            $stmt->close();
        } catch (Exception $e) {
            error_log("Exception in loading user data: " . $e->getMessage());
        }
    }
}

error_log("=== END PROFILE UPDATE ===");
?> 