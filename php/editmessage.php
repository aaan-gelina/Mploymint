<?php
require '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['messages'])) {
    foreach ($_POST['messages'] as $mid => $msgData) {
        $did = $msgData['did'] ?? '';
        $senderid = $msgData['senderid'] ?? '';
        $text = $msgData['text'] ?? '';
        $timesent = $msgData['timesent'] ?? '';

        // Fetch previous values
        $prevStmt = $db->prepare("SELECT did, senderid, text, timesent FROM message WHERE mid = ?");
        $prevStmt->bind_param("i", $mid);
        $prevStmt->execute();
        $prevStmt->bind_result($prev_did, $prev_senderid, $prev_text, $prev_timesent);
        $prevStmt->fetch();
        $prevStmt->close();

        $prev_value = json_encode([
            "did" => $prev_did,
            "senderid" => $prev_senderid,
            "text" => $prev_text,
            "timesent" => $prev_timesent
        ]);

        $new_value = json_encode([
            "did" => $did,
            "senderid" => $senderid,
            "text" => $text,
            "timesent" => $timesent
        ]);

        // Update message
        $stmt = $db->prepare("UPDATE message SET did = ?, senderid = ?, text = ?, timesent = ? WHERE mid = ?");
        $stmt->bind_param("iissi", $did, $senderid, $text, $timesent, $mid);
        $stmt->execute();
        $stmt->close();

        // Insert audit log
        $audit = $db->prepare("
            INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value)
            VALUES (?, ?, 'Update', 'Batch message update', 'message', 'UPDATE', ?, ?)
        ");
        $audit->bind_param("ssss", $mid, $text, $prev_value, $new_value);
        $audit->execute();
        $audit->close();
    }

    echo json_encode(["message" => "All messages updated successfully."]);
}
?>