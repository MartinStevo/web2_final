<?php
include_once("config.php");
require_once('libs/fpdf182/fpdf.php');

session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php?login=none");
    exit;
}
$login = $_SESSION["login"];
$accessType = $_SESSION["accessType"];


$text = 'Využívanie našich API služieb si vyžaduje vytvoriť používateľský účet. Pre vytvorenie nového účtu je potrebné sa registrovať prostredníctvom našej registrácie alebo sa môžete prihlásiť pomocou Google účtu alebo ldap. Po vytvorení účtu vám bude vygenerovaný API kľúč, ktorý vám je po prihlásení k dispozícii v nastaveniach. Tento API kľúč si môžete kedykoľvek resetovať.
Dáta z prostredia Octave sú posielané v JSON formáte. Pre ich získanie je potrebné zadať váš API kľúč. Na tejto stránke uvádzame volania pre správny prístup k službe API.
Pri simulátore tlmiča automobilu je potrebné zadať výšku prekážky r. API volanie:
https://wt28.fei.stuba.sk:4428/web2_final/api/vehicle?apikey={váš_api_kľúč}r={vaše_r}
Pri simulátore prevráteného kyvadla je potrebné zadať pozíciu kyvadla r. API volanie:
https://wt28.fei.stuba.sk:4428/web2_final/api/pendulum?apikey={váš_api_kľúč}r={vaše_r}
Pri simulátore guličky na tyči je potrebné zadať pozíciu guličky r. API volanie:
https://wt28.fei.stuba.sk:4428/web2_final/api/ball?apikey={váš_api_kľúč}r={vaše_r}
Pri simulátore lietadla je potrebné zadať náklon lietadla r. API volanie:
Ahttps://wt28.fei.stuba.sk:4428/web2_final/api/plane?apikey={váš_api_kľúč}r={vaše_r}';
$text = utf8_decode($text);
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Write(6,$text);
$pdf->Output();
?>

