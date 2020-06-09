<?php


//todo 
//1.1 gen doc
//1.2 gen queries
//1.3 gen statististics
//1.4 
//include connection file 
include_once("config.php");
include_once('libs/fpdf182/fpdf.php');

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php?login=none");
    exit;
}
$login = $_SESSION["login"];
$accessType = $_SESSION["accessType"];
$apikey = $_SESSION["apikey"];

class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('img/logo.png',10,10,70); 
        $this->SetFont('Arial', 'B', 13);
        // Move to the right
        $this->Cell(80);
        // Title
        $this->Cell(300, 10, 'Queries of ' . $_SESSION["login"], 1, 1, 'L');
        // Line break
        $this->Ln(20);
    }

    // Page footer
    function Footer()
    {
        // Position at 1.5 cm from bottom
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Page number
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}



    $stmt = $conn->prepare("SELECT date,success,error,query,form,id FROM Queries WHERE apikey=?");
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



$pdf = new PDF();
//header
$pdf->AddPage();
//foter page
$pdf->AliasNbPages();

$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(10, 6, 'Id', 1);
$pdf->Cell(25, 6, 'Date', 1);
$pdf->Cell(10, 6, 'Success', 1);
$pdf->Cell(40, 6, 'Error', 1);
$pdf->Cell(50, 6, 'Query', 1);
$pdf->Cell(25, 6, 'Form', 1);

while ($row = $qresult->fetch_assoc()) {
    $pdf->Ln();
    $pdf->Cell(10, 6, $row["id"], 1);
    $pdf->Cell(25, 6, $row["date"], 1);
    $pdf->Cell(10, 6, $row["success"], 1);
    $pdf->Cell(40, 6, $row["error"], 1);
    $pdf->Cell(50, 6, $row["query"], 1);
    $pdf->Cell(25, 6, $row["form"], 1);
    
}

$pdf->Output("OfficeForm.pdf", "I");
?>
