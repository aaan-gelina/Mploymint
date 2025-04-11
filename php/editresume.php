<?php
require '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resumes'])) {
    foreach ($_POST['resumes'] as $rid => $resumeData) {
        $uid = $resumeData['uid'] ?? '';
        $filename = $resumeData['filename'] ?? '';

        // Fetch previous values
        $prevStmt = $db->prepare("SELECT uid, filename FROM resume WHERE rid = ?");
        $prevStmt->bind_param("i", $rid);
        $prevStmt->execute();
        $prevStmt->bind_result($prev_uid, $prev_filename);
        $prevStmt->fetch();
        $prevStmt->close();

        $prev_value = json_encode([
            "uid" => $prev_uid,
            "filename" => $prev_filename
        ]);

        $new_value = json_encode([
            "uid" => $uid,
            "filename" => $filename
        ]);

        // Update resume
        $stmt = $db->prepare("UPDATE resume SET uid = ?, filename = ? WHERE rid = ?");
        $stmt->bind_param("isi", $uid, $filename, $rid);
        $stmt->execute();
        $stmt->close();

        // Insert audit log
        $audit = $db->prepare("
            INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value)
            VALUES (?, ?, 'Update', 'Batch resume update', 'resume', 'UPDATE', ?, ?)
        ");
        $audit->bind_param("ssss", $rid, $filename, $prev_value, $new_value);
        $audit->execute();
        $audit->close();
    }

    echo json_encode(["message" => "All resumes updated successfully."]);
}
