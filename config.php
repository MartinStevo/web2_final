<?php
    $servername_t = "localhost";
    $username_t = "xondreakova";
    $password = "h7g3Mn9k";
    $dbname_t = "autentifikacia";
    $conn = new mysqli($servername_t, $username_t, $password, $dbname_t);
    mysqli_set_charset($conn, "utf8");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
