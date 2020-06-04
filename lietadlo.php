<?php 
require_once('config.php');
require_once('statistics.php'); ?>

<?php
require_once('config.php');
require_once('statistics.php'); ?>

<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php?login=none");
} else if (isset($_GET["action"])) {
    if ($_GET["action"] == "logout") {
        session_unset();
        header("Location: index.php?login=loggedOut");
    }
}
$login = $_SESSION["login"];
$page = "airplane.php";
insert_page($conn,$page,$login);
?>

<!DOCTYPE html>
<html lang="sk">

<head>
    <title>Alica Ondreakova Page</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="css/login.css">
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/dict.js"></script>
    <script type="text/javascript" src="js/settings.js"></script>
    <meta name_t="viewport" content="width=device-width, initial-scale=1.0">
    <meta name_t="keywords" content="html, css">
    <meta name_t="author" content="Alica Ondreakova">
</head>

<div class="background_1">
    <?php require_once('widgets/nav.php'); ?>
</div>


<body>
</body>
</html>