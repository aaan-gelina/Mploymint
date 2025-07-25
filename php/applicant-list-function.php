<?php
session_start();
include_once dirname(__FILE__) . '/../dbconnect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login.php");
    exit();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $aid = $_POST['aid'];
    $status = $_POST['status'];
    $jid = $_POST['jid'];
    
    $update_stmt = $db->prepare("UPDATE application SET status = ? WHERE aid = ?");
    $update_stmt->bind_param("si", $status, $aid);
    
    if ($update_stmt->execute()) {
        // Redirect to avoid form resubmission
        header("Location: ../applicant_list.php?status_updated=1");
        exit();
    }
}

$applicants = [];
$job = null;
$jid = 0;
$company_jobs = [];


if (isset($_SESSION['uid']) && $_SESSION['type'] === 'company') {
    $cid = $_SESSION['uid'];
    

    $jobs_query = "SELECT jid, title FROM job WHERE cid = ? AND archive = 0";
    $stmt = $db->prepare($jobs_query);
    $stmt->bind_param("i", $cid);
    $stmt->execute();
    $jobs_result = $stmt->get_result();
    
    if ($jobs_result->num_rows > 0) {
        while ($job_row = $jobs_result->fetch_assoc()) {
            $company_jobs[$job_row['jid']] = $job_row['title'];
        }
    }
    

    if (isset($_GET['jid']) && !empty($_GET['jid'])) {
        $jid = intval($_GET['jid']);
        
  
        $job_query = "SELECT j.*, u.name as company 
                    FROM job j 
                    JOIN user u ON j.cid = u.uid 
                    WHERE j.jid = ? AND j.archive = 0";
        $stmt = $db->prepare($job_query);
        $stmt->bind_param("i", $jid);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $job = $result->fetch_assoc();
        }
        
        
        $where_clause = "a.jid = ?";
        $param_type = "i";
        $param_value = $jid;
    } else {
        
        $job_ids = array_keys($company_jobs);
        if (empty($job_ids)) {
            return; 
        }
        
        $where_clause = "a.jid IN (" . implode(',', array_fill(0, count($job_ids), '?')) . ")";
        $param_type = str_repeat("i", count($job_ids));
        $param_value = $job_ids;
    }
    
  
    $applicant_query = "SELECT u.name, u.email, u.uid, a.status, r.filename as resume_filename, a.aid, a.jid, j.title as job_title,
                        (SELECT timestamp FROM audit_log 
                         WHERE db_table = 'application' AND operation = 'INSERT' 
                         AND new_value LIKE CONCAT('%\"jid\":', a.jid, '%')
                         AND new_value LIKE CONCAT('%\"uid\":', a.uid, '%')
                         ORDER BY timestamp DESC LIMIT 1) as applied_date
                        FROM application a 
                        JOIN user u ON a.uid = u.uid 
                        JOIN job j ON a.jid = j.jid
                        LEFT JOIN resume r ON r.uid = u.uid AND r.archive = 0
                        WHERE $where_clause AND a.archive = 0 
                        ORDER BY a.aid DESC";
    
    $stmt = $db->prepare($applicant_query);
    
    
    if (isset($_GET['jid']) && !empty($_GET['jid'])) {
        $stmt->bind_param($param_type, $param_value);
    } else {
        try {
         
            $bind_names = array();
            $bind_names[] = $param_type;
            for ($i = 0; $i < count($param_value); $i++) {
                $bind_name = 'param' . $i;
                $$bind_name = $param_value[$i];
                $bind_names[] = &$$bind_name;
            }
            call_user_func_array(array($stmt, 'bind_param'), $bind_names);
        } catch (Exception $e) {
            error_log("Error binding parameters: " . $e->getMessage());
         
        }
    }
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            
            if (!empty($row['resume_filename'])) {
             
                $row['resume'] = '../uploads/resumes/' . $row['resume_filename'];
                $row['has_resume'] = true;
                
              
                $resumePath = dirname(__FILE__) . '/../uploads/resumes/' . $row['resume_filename'];
                $row['resume_exists'] = file_exists($resumePath);
            } else {
                $row['resume'] = '#';
                $row['has_resume'] = false;
                $row['resume_exists'] = false;
            }
            unset($row['resume_filename']); 
            $applicants[] = $row;
        }
    }
}
?>