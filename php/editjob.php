<?php
require '../dbconnect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['jobs'])) {
    foreach ($_POST['jobs'] as $jid => $jobData) {
        $cid = $jobData['cid'] ?? '';
        $curl = $jobData['curl'] ?? '';
        $title = $jobData['title'] ?? '';
        $category = $jobData['category'] ?? '';
        $type = $jobData['type'] ?? '';
        $location = $jobData['location'] ?? '';
        $salary = $jobData['salary'] ?? '';
        $experience = $jobData['experience'] ?? '';
        $appdeadline = $jobData['appdeadline'] ?? '';
        $appurl = $jobData['appurl'] ?? '';
        $description = $jobData['description'] ?? '';
        $requs = $jobData['requs'] ?? '';
        $resps = $jobData['resps'] ?? '';
        $status = $jobData['status'] ?? '';

        // Fetch previous values for audit
        $prevStmt = $db->prepare("SELECT cid, curl, title, category, type, location, salary, experience, appdeadline, appurl, description, requs, resps, status FROM job WHERE jid = ?");
        $prevStmt->bind_param("i", $jid);
        $prevStmt->execute();
        $prevStmt->bind_result($prev_cid, $prev_curl, $prev_title, $prev_category, $prev_type, $prev_location, $prev_salary, $prev_experience, $prev_appdeadline, $prev_appurl, $prev_description, $prev_requs, $prev_resps, $prev_status);
        $prevStmt->fetch();
        $prevStmt->close();

        $prev_value = json_encode([
            "cid" => $prev_cid,
            "curl" => $prev_curl,
            "title" => $prev_title,
            "category" => $prev_category,
            "type" => $prev_type,
            "location" => $prev_location,
            "salary" => $prev_salary,
            "experience" => $prev_experience,
            "appdeadline" => $prev_appdeadline,
            "appurl" => $prev_appurl,
            "description" => $prev_description,
            "requs" => $prev_requs,
            "resps" => $prev_resps,
            "status" => $prev_status
        ]);

        $new_value = json_encode([
            "cid" => $cid,
            "curl" => $curl,
            "title" => $title,
            "category" => $category,
            "type" => $type,
            "location" => $location,
            "salary" => $salary,
            "experience" => $experience,
            "appdeadline" => $appdeadline,
            "appurl" => $appurl,
            "description" => $description,
            "requs" => $requs,
            "resps" => $resps,
            "status" => $status
        ]);

        // Update job
        $stmt = $db->prepare("UPDATE job SET cid = ?, curl = ?, title = ?, category = ?, type = ?, location = ?, salary = ?, experience = ?, appdeadline = ?, appurl = ?, description = ?, requs = ?, resps = ?, status = ? WHERE jid = ?");
        $stmt->bind_param("isssssssssssssi", $cid, $curl, $title, $category, $type, $location, $salary, $experience, $appdeadline, $appurl, $description, $requs, $resps, $status, $jid);
        $stmt->execute();
        $stmt->close();

        // Insert audit log
        $audit = $db->prepare("
            INSERT INTO audit_log (uid, email, action, description, db_table, operation, prev_value, new_value)
            VALUES (?, ?, 'Update', 'Batch job update', 'job', 'UPDATE', ?, ?)
        ");
        $audit->bind_param("ssss", $jid, $title, $prev_value, $new_value);
        $audit->execute();
        $audit->close();
    }

    echo json_encode(["message" => "All jobs updated successfully."]);
}
?>