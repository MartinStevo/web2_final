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

    <title>FINAL PROJECT</title>

    <meta name_t="viewport" content="width=device-width, initial-scale=1.0">
    <meta name_t="keywords" content="html, css">
    <meta name_t="author" content="Alica Ondreakova">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/dict.js"></script>
    <script type="text/javascript" src="js/settings.js"></script>


</head>

<body>


<div class="background_1">
    <?php require_once('widgets/nav.php'); ?>
</div>

<div id="user_container">


    <div id="statistics">
        <?php require_once('widgets/gui_stats.php'); ?>

    </div>

    <div id="settings" style="margin-left:20px">

        <?php require_once('widgets/gui_sett.php'); ?>
    </div>

    <div style="background-color: white;padding:20px;">

   

    <section id="section1" style="margin:30px">
        <h1 class="lang" key="doc"></h1>
    </section>
        


        <section id="section1" style="margin:30px">

            <text class="lang" key="getStart-text">
            </text>

        </section>

        <section id="section2" style="margin:30px">

            <text class="lang" key="sims-text">
            </text>

        </section>

        <section id="section3" style="margin:30px">

            <text class="lang" key="s1-text">
            </text>
            <br>
            <text class="lang" key="s1-text-api-call">
            </text>

        </section>

        <section id="section4" style="margin:30px">

            <text class="lang" key="s2-text">
            </text>
            <br>
            <text class="lang" key="s2-text-api-call">
            </text>

        </section>

        <section id="section5" style="margin:30px">

            <text class="lang" key="s3-text">
            </text>
            <br>
            <text class="lang" key="s3-text-api-call">
            </text>

        </section>

        <section id="section6" style="margin:30px">

            <text class="lang" key="s4-text">
            </text>
            <br>
            <text class="lang" key="s4-text-api-call">
            </text>

        </section>
       
        <img id="download_doc" width=150px heigh="100px" src="img/pdficon.png" alt="pdf_download" style="margin: 30px" >
    </div>


</body>

</html>