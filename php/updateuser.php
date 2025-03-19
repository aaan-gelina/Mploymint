<?php
    session_start();
    include $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/dbconnect.php';

    header('Content-Type: application/json');

    if (!isset($_SESSION['uid'])) {
        echo json_encode(["message" => "You must log in to update your account."]);
        exit;
    }

    $uid = $_SESSION['uid'];
    $email = isset($_POST['email']) ? trim($_POST['email']) : '';
    $firstname = isset($_POST['firstname']) ? trim($_POST['firstname']) : '';
    $lastname = isset($_POST['lastname']) ? trim($_POST['lastname']) : '';
    $description = isset($_POST['description']) ? trim($_POST['description']) : '';

    if (empty($email) || empty($firstname) || empty($lastname) || empty($description)) {
        echo json_encode(["message" => "All fields are required."]);
        exit;
    }

    //Fetch values for audit
    $fetchStmt = $db->prepare("SELECT email, name, description FROM user WHERE uid = ?");
    $fetchStmt->bind_param("i", $uid);
    $fetchStmt->execute();
    $fetchStmt->bind_result($old_email, $old_name, $old_description);
    $fetchStmt->fetch();
    $fetchStmt->close();

    $prev_value = json_encode([
        "email" => $old_email, 
        "name" => $old_name, 
        "description" => $old_description
    ]);

    $name = $firstname . "`" . $lastname;

    $new_value = json_encode([
        "email" => $email, 
        "name" => $name,
        "description" => $description
    ]);

    //Update user info in database
    $updateStmt = $db->prepare("UPDATE user SET email = ?, name = ?, description = ? WHERE uid = ?");
    $updateStmt->bind_param("sssi", $email, $name, $description, $uid);

    if ($updateStmt->execute()) {
        //Create audit
        $auditStmt = $db->prepare("
            INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value) 
            VALUES (?, ?, 'Update', 'User updated account info', 'user', 'UPDATE', ?, ?)
        ");
        $auditStmt->bind_param("ssss", $uid, $email, $prev_value, $new_value);
        $auditStmt->execute();
        $auditStmt->close();

        echo json_encode(["message" => "Account updated successfully!"]);
    } else {
        echo json_encode(["message" => "Error updating account. Please try again."]);
    }

    $updateStmt->close();
?>
