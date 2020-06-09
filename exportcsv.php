<?php

error_reporting(E_ALL);
require_once('phpconfig/userses.php');
include_once('config.php');
session_start();
/*
$password = "Preco666";
$servername = "localhost";
$username = "Kurbo";
$dbname = "final";
*/ 
//////////////////////////////////////////
$apikey = $_SESSION["apikey"];

$stmt = $conn->prepare("SELECT date,success,error,query,form FROM Queries WHERE apikey=?");
    if (!$stmt) {
        die("Db error: " . $conn->error);
    }
    $stmt->bind_param('s', $apikey);
    if (!$stmt->execute()) {
        die("Db error: " . $stmt->error);
    }

    $qresult = $stmt->get_result();
    if (!$qresult) {
        echo $conn->error;
    }

    // filename for export
    $csv_filename = 'db_export_'.date('Y-m-d').'.csv';

    // query to get data from database
    //$sql = ("SELECT * FROM ".$dbtarget);/." ".$where);
    

    // Export the data and prompt a csv file for download
    header("Content-type: text/x-csv");
    header("Content-Disposition: attachment; filename=".$csv_filename."");
    $output = fopen('php://output', 'w');
    fputcsv($output, array("Date", "Success", "Error", "Query", "Form"));
    while ($row = $qresult->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    

?>