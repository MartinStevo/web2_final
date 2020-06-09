<?php

include_once('config.php');
/*
$password = "Preco666";
$servername = "localhost";
$username = "Kurbo";
$dbname = "final";
*/ 
//////////////////////////////////////////

if( isset($_POST["dbtarget"]) ){

    // filename for export
    $csv_filename = 'db_export_'.date('Y-m-d').'.csv';

    // optional where query and name of db
    $where = 'WHERE 1 ORDER BY 1';
    $dbtarget = $_POST["dbtarget"];

    //Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    mysqli_set_charset($conn, "utf8");


    // query to get data from database
    $sql = ("SELECT * FROM ".$dbtarget);//." ".$where);

    // Export the data and prompt a csv file for download
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=".$csv_filename."");
    $output = fopen('php://output', 'w');

    $result = $conn->query($sql);

    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    mysqli_close($conn);
    
}
?>