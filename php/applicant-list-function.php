<?php
session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/Mploymint/dbconnect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

$applicants = [];
$applicant_query = "SELECT u.name, u.email, a.resume, a.status 
                    FROM application a 
                    JOIN user u ON a.uid = u.uid 
                    WHERE a.jid = ? AND a.archive = 0 
                    ORDER BY a.aid DESC";

if (isset($_GET['jid'])) {
    $jid = $_GET['jid'];
    $stmt = $db->prepare($applicant_query);
    $stmt->bind_param("i", $jid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $applicants[] = $row;
        }
    }
}
?>