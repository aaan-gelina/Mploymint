<?php
    $user = 'root';
    $pass = '';
    $db = 'mploymintdb';

    $db = new mysqli('localhost', $user, $pass, $db) or die("Unable to connect.");
?>