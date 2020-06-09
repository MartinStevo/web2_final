<?php

include_once("config.php");
require_once('libs/fpdf182/fpdf.php');

/*
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php?login=none");
    exit;
}
$login = $_SESSION["login"];
$accessType = $_SESSION["accessType"];




$text = 'Use of our API services requires the user account. To create a new account, you need to sign up  or you can log in using a Google account or ldap. API key will be generated for you, as soon as your account is created. This API key is available for you in settings section and you can reset it any time.Octave data are responsed in JSON file format. You need to add your API key to obtain them. The correct form of API calls are listed on this page.
It is necessary to add the height of the obstacle r in the car suspension system simulator. API call:https://wt28.fei.stuba.sk:4428/web2_final/api/vehicle?apikey={your_api_key}r={r}
It is necessary to add the position of the pendulum r in the inverted pendulum simulator. API call:
https://wt28.fei.stuba.sk:4428/web2_final/api/pendulum?apikey={your_api_key}r={r}
It is necessary to add the position of the ball r in the beam and ball simulator. API call:
https://wt28.fei.stuba.sk:4428/web2_final/api/ball?apikey={your_api_key}r={r}
It is necessary to add the pitch of the aircraft r in the aircraft pitch control simulator. API call:
https://wt28.fei.stuba.sk:4428/web2_final/api/plane?apikey={your_api_key}r={r}';




$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',14);
$pdf->SetTitle('Documentation');
$pdf->write(6,$text);
$pdf->Output('D','Documentation.pdf');
?>
*/


class PDF extends FPDF
{
function Header()
{
    global $title;

    $this->Image('img/logo.png',10,10,70); 
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Calculate width of title and position
    $w = $this->GetStringWidth($title)+14;
    $this->SetX((210-$w)/2);
    // Colors of frame, background and text
    $this->SetFillColor(200,220,255);
    // Thickness of frame (1 mm)
    $this->SetLineWidth(1);
    // Title
    $this->Cell($w,9,$title,1,1,'C',true);
    // Line break
    $this->Ln(10);
}

function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Text color in gray
    $this->SetTextColor(128);
    // Page number
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function ChapterTitle($num, $label)
{
    // Arial 12
    $this->SetFont('Arial','',10);
    // Background color
    $this->SetFillColor(200,220,255);
    // Title
    $this->Cell(0,6,"Chapter $num : $label",0,1,'L',true);
    // Line break
    $this->Ln(4);
}

function ChapterBody($file)
{
    // Read text file
    $txt = file_get_contents($file);
    // Times 12
    $this->SetFont('Times','',12);
    // Output justified text
    $this->MultiCell(0,6,$txt);
    // Line break
    $this->Ln();
    // Mention in italics
    $this->SetFont('','I');
    $this->Cell(0,5,'(end of documentation:for more info xondreakova@stuba.sk)');
}

function PrintChapter($num, $title, $file)
{
    $this->AddPage();
    $this->ChapterTitle($num,$title);
    $this->ChapterBody($file);
}
}

$pdf = new PDF();
$title = 'Documentation';
$pdf->SetTitle($title);
$pdf->SetAuthor('Our api team');
$pdf->PrintChapter(1,'Documentation','eng_doc.txt');
$pdf->Output('D','Documentation.pdf');
?>
