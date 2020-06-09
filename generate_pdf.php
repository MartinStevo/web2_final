
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
        $this->Cell(300, 10, 'Login history of user ' . $_SESSION["login"], 1, 1, 'L');
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



$stmt = $conn->prepare("SELECT * FROM Prihlasenia WHERE login=? AND accessType=? ORDER BY time DESC");
if (!$stmt) {
    die($conn->error);
}
$stmt->bind_param('ss', $login, $accessType);
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
$pdf->Cell(50, 6, 'Time', 1);
$pdf->Cell(80, 6, 'Login', 1);
$pdf->Cell(30, 6, 'accessType', 1);

while ($row = $qresult->fetch_assoc()) {
    $pdf->Ln();
    $pdf->Cell(10, 6, $row["id"], 1);
    $pdf->Cell(50, 6, $row["time"], 1);
    $pdf->Cell(80, 6, $row["login"], 1);
    if ($row["accessType"] == 0) {
        $pdf->Cell(30, 6, 'registered', 1);
    } elseif ($row["accessType"] == 1) {
        $pdf->Cell(30, 6, 'ldap', 1);
    } elseif ($row["accessType"] == 2) {
        $pdf->Cell(30, 6, 'google', 1);
    }
}
$pdf->Output("OfficeForm.pdf", "I");
?>
