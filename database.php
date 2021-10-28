<?php

function openConn(){
    session_start();
    $db_user = "root";
    $db_pw = "******";
    $server = "localhost";
    $db = "burnlearn";
    $conn = new mysqli($server, $db_user, $db_pw, $db);
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }
    return $conn;
}

