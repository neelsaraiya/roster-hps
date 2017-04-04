<?php

function dbconn(){
    $servername = "localhost";
    $username = "neelsara_user";
    $password = "bl*T6T7Z.L;C";
    $dbname = "neelsara_sivaniroster";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    return $conn;
}