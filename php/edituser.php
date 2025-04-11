<?php
require '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['users'])) {
    foreach ($_POST['users'] as $uid => $userData) {
        $email = $userData['email'] ?? '';
        $name = $userData['name'] ?? '';
        $type = $userData['type'] ?? '';
        $password = $userData['password'] ?? '';
        $profileimg = $userData['profileimg'] ?? '';
        $description = $userData['description'] ?? '';

        // Fetch previous values for audit
        $prevStmt = $db->prepare("SELECT email, name, type, password, profileimg, description FROM user WHERE uid = ?");
        $prevStmt->bind_param("i", $uid);
        $prevStmt->execute();
        $prevStmt->bind_result($prev_email, $prev_name, $prev_type, $prev_password, $prev_profileimg, $prev_description);
        $prevStmt->fetch();
        $prevStmt->close();

        $prev_value = json_encode([
            "email" => $prev_email,
            "name" => $prev_name,
            "type" => $prev_type,
            "password" => $prev_password,
            "profileimg" => $prev_profileimg,
            "description" => $prev_description
        ]);

        $new_value = json_encode([
            "email" => $email,
            "name" => $name,
            "type" => $type,
            "password" => $password,
            "profileimg" => $profileimg,
            "description" => $description
        ]);

        // Update user
        $stmt = $db->prepare("UPDATE user SET email = ?, name = ?, type = ?, password = ?, profileimg = ?, description = ? WHERE uid = ?");
        $stmt->bind_param("ssssssi", $email, $name, $type, $password, $profileimg, $description, $uid);
        $stmt->execute();
        $stmt->close();

        // Insert audit log
        $audit = $db->prepare("
            INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value)
            VALUES (?, ?, 'Update', 'Batch user update', 'user', 'UPDATE', ?, ?)
        ");
        $audit->bind_param("ssss", $uid, $email, $prev_value, $new_value);
        $audit->execute();
        $audit->close();
    }

    echo json_encode(["message" => "All users updated successfully."]);
}
?>


