<?php require_once('config.php');
require_once('statistics.php');
?>
<?php
session_start();

if (!isset($_SESSION["login"])) {
    header("Location: index.php?login=none");
    exit;
} else if (isset($_GET["action"])) {
    if ($_GET["action"] == "logout") {
        session_unset();
        header("Location: index.php?login=loggedOut");
        exit;
    }
    echo "Hello world!";
$key = check_if_user_has_key($conn, $login);
if ($key == false) {
    insert_apikey($conn, $login);
}




?>
<!DOCTYPE html>
<html lang="sk">


<head>
    <title>Final</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="shortcut icon" type="image/png" href="other/favicon.ico" />
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/dict.js"></script>
    <script type="text/javascript" src="js/settings.js"></script>

</head>


<body>


    <?php require_once('widgets/nav.php'); ?>

    <div id="user_container">


        <!-- <p>You are logged in as <?php echo $_SESSION["login"]; ?></p> -->
        <div id="statistics">
            <?php require_once('widgets/gui_stats.php'); ?>

        </div>

        <div id="settings" style="margin-left:20px">

            <?php require_once('widgets/gui_sett.php'); ?>
        </div>






    </div>
</body>

</html>