<?php
    $servername_t = "localhost";
    $username_t = "xondreakova";
    $password = "*********";
    $dbname_t = "autentifikacia";
    $conn = new mysqli($servername_t, $username_t, $password, $dbname_t);
    mysqli_set_charset($conn, "utf8");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    //Kazdy bude mat zvlast config, pre svoju dbs, a tieto pathy zatial,mozno este nieco pribudne, 
    //config sa nebude uz commitovat, kazdy si spravi podla vzoru
    $gansta_path = "../../google/google-api-php-client-2.4.0/vendor/autoload.php";
    $json_cred = '../../google/credentials.json';
    $red_uri = 'http://wt221.fei.stuba.sk:8221/web2_final/index.php';
    $auth_php = '../../google/GoogleAuthenticator-1.0.1/PHPGangsta/GoogleAuthenticator.php';
    $my_site = 'http://wt221.fei.stuba.sk:8221/web2_final/index.php';

?>