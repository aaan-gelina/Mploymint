<?php
session_start();
include 'dbconnect.php';

$jobs = [];
$job_query = "SELECT jid, title, location, type, salary FROM job WHERE status = 'active' AND archive = 0 ORDER BY jid DESC";
$job_result = $db->query($job_query);

if ($job_result->num_rows > 0) {
  while ($row = $job_result->fetch_assoc()) {
    $jobs[] = $row;
  }
}