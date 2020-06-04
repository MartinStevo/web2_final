<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php?login=none");
    exit;
} else if (isset($_GET["action"])) {
    if ($_GET["action"] == "logout") {
        session_unset();
        header("Location: index.php?login=loggedOut");
    }
}
?>
<!DOCTYPE html>
<html lang="sk">

<head>
    <title>Alica Ondreakova Page</title>

    <meta name_t="viewport" content="width=device-width, initial-scale=1.0">
    <meta name_t="keywords" content="html, css">
    <meta name_t="author" content="Alica Ondreakova">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/dict.js"></script>




</head>

<body>


    <div class="background_1">
        <?php require_once('widgets/nav.php'); ?>
    </div>

    <div style="background-color: white;padding:20px;">
    <h1>Documentation</h1>
    <a href="#section1" key="getStart" class="lang" style="font-weight: bold;"></a><br>
    <a href="#section2" key="sims" class="lang"></a><br>
    <a href="#section3" class="lang" key="s2-section"></a><br>
    <a href="#section4" class="lang" key="s3-section"></a><br>
    <a href="#section5" class="lang" key="s1-section"></a><br>
    <a href="#section6" class="lang" key="s4-section"></a><br>


<section id="section1" style="margin:30px">

<text>Our application started as final project for subject:Web technologies II. Since that time we didn't really do anything with the apllication... 
    We want to show you what is student of MSUS capable of after corona crysis..
But it's here for out teachers,trainers and thesis masters to see.</text>
</section>

<section id="section2" style="margin:30px">

<text>
    hjkchkdjhsjkdhaskjdhaskjdhaskjdhaskjdhaskjdhsajkdhsakjdhasjkdaskdhaskjhdashdasdhasd
    dkjsahdaskjdsakhdsdhaskhdasuhdaskdjshadas
    dhhsjkdhasmhdsadhaskjdhaskhdahda
    jdkashdhsadkljashdashdakhdhksada

</text>

</section>

<section id="section3" style="margin:30px">

<text>
hjkchkdjhsjkdhaskjdhaskjdhaskjdhaskjdhaskjdhsajkdhsakjdhasjkdaskdhaskjhdashdasdhasd
    dkjsahdaskjdsakhdsdhaskhdasuhdaskdjshadas
    dhhsjkdhasmhdsadhaskjdhaskhdahda
    jdkashdhsadkljashdashdakhdhksada
</text>
    
</section>

<section id="section4" style="margin:30px">

<text>

</text>
</section>

<section id="section5" style="margin:30px">

<text>
hjkchkdjhsjkdhaskjdhaskjdhaskjdhaskjdhaskjdhsajkdhsakjdhasjkdaskdhaskjhdashdasdhasd
    dkjsahdaskjdsakhdsdhaskhdasuhdaskdjshadas
    dhhsjkdhasmhdsadhaskjdhaskhdahda
    jdkashdhsadkljashdashdakhdhksada
</text>
</section>

<section id="section6" style="margin:30px">

<text>
hjkchkdjhsjkdhaskjdhaskjdhaskjdhaskjdhaskjdhsajkdhsakjdhasjkdaskdhaskjhdashdasdhasd
    dkjsahdaskjdsakhdsdhaskhdasuhdaskdjshadas
    dhhsjkdhasmhdsadhaskjdhaskhdahda
    jdkashdhsadkljashdashdakhdhksada
</text>
</section>
    </div>



</body>

</html>