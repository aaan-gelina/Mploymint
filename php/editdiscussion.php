<?php
require '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['discussions'])) {
    foreach ($_POST['discussions'] as $did => $discussionData) {
        $title = $discussionData['title'] ?? '';
        $creatorid = $discussionData['creatorid'] ?? '';
        $members = $discussionData['members'] ?? '';
        $taglist = $discussionData['taglist'] ?? '';
        $description = $discussionData['description'] ?? '';

        // Fetch previous values for audit
        $prevStmt = $db->prepare("SELECT title, creatorid, members, taglist, description FROM discussion WHERE did = ?");
        $prevStmt->bind_param("i", $did);
        $prevStmt->execute();
        $prevStmt->bind_result($prev_title, $prev_creatorid, $prev_members, $prev_taglist, $prev_description);
        $prevStmt->fetch();
        $prevStmt->close();

        $prev_value = json_encode([
            "title" => $prev_title,
            "creatorid" => $prev_creatorid,
            "members" => $prev_members,
            "taglist" => $prev_taglist,
            "description" => $prev_description
        ]);

        $new_value = json_encode([
            "title" => $title,
            "creatorid" => $creatorid,
            "members" => $members,
            "taglist" => $taglist,
            "description" => $description
        ]);

        // Update discussion
        $stmt = $db->prepare("UPDATE discussion SET title = ?, creatorid = ?, members = ?, taglist = ?, description = ? WHERE did = ?");
        $stmt->bind_param("sisssi", $title, $creatorid, $members, $taglist, $description, $did);
        $stmt->execute();
        $stmt->close();

        // Insert audit log
        $audit = $db->prepare("
            INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value)
            VALUES (?, ?, 'Update', 'Batch discussion update', 'discussion', 'UPDATE', ?, ?)
        ");
        $audit->bind_param("ssss", $did, $title, $prev_value, $new_value);
        $audit->execute();
        $audit->close();
    }

    echo json_encode(["message" => "All discussions updated successfully."]);
}
?>
