
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





</body>

</html>