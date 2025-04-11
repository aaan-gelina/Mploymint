<?php
require '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['applications'])) {
    foreach ($_POST['applications'] as $aid => $appData) {
        $jid = $appData['jid'] ?? '';
        $uid = $appData['uid'] ?? '';
        $cid = $appData['cid'] ?? '';
        $status = $appData['status'] ?? '';

        // Fetch previous values for audit
        $prevStmt = $db->prepare("SELECT jid, uid, cid, status FROM application WHERE aid = ?");
        $prevStmt->bind_param("i", $aid);
        $prevStmt->execute();
        $prevStmt->bind_result($prev_jid, $prev_uid, $prev_cid, $prev_status);
        $prevStmt->fetch();
        $prevStmt->close();

        $prev_value = json_encode([
            "jid" => $prev_jid,
            "uid" => $prev_uid,
            "cid" => $prev_cid,
            "status" => $prev_status
        ]);

        $new_value = json_encode([
            "jid" => $jid,
            "uid" => $uid,
            "cid" => $cid,
            "status" => $status
        ]);

        // Update application
        $stmt = $db->prepare("UPDATE application SET jid = ?, uid = ?, cid = ?, status = ? WHERE aid = ?");
        $stmt->bind_param("iiisi", $jid, $uid, $cid, $status, $aid);
        $stmt->execute();
        $stmt->close();

        // Insert audit log
        $audit = $db->prepare("
            INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value)
            VALUES (?, ?, 'Update', 'Batch application update', 'application', 'UPDATE', ?, ?)
        ");
        $audit->bind_param("ssss", $aid, $status, $prev_value, $new_value);
        $audit->execute();
        $audit->close();
    }

    echo json_encode(["message" => "All applications updated successfully."]);
}
?>