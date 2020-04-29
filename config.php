<?php
    $servername_t = "localhost";
    $username_t = "admin_martin";
    $password = "Chivasko1*";
    $dbname_t = "autentifikacia";
    $conn = new mysqli($servername_t, $username_t, $password, $dbname_t);
    mysqli_set_charset($conn, "utf8");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    //Kazdy bude mat zvlast config, pre svoju dbs, a tieto pathy zatial,mozno este nieco pribudne, 
    //config sa nebude uz commitovat, kazdy si spravi podla vzoru
    $gansta_path = "../z3/vendor/autoload.php";
    $json_cred = '../files/credentials_1588103164.json';
    $red_uri = 'https://wt28.fei.stuba.sk:4428/web2_final/index.php';
    $auth_php = '../z3/2FA/PHPGangsta/GoogleAuthenticator.php';
    $my_site = 'https://wt28.fei.stuba.sk:4428/web2_final/index.php';

?>