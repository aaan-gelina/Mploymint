<?php
    $db_user = 'kkim9901';
    $db_pass = 'kkim9901';
    $db_name = 'kkim9901';

    $db = new mysqli('localhost', $db_user, $db_pass, $db_name);

    if ($db->connect_error) {
        die("Connection failed: " . $db->connect_error);
    }
?>
