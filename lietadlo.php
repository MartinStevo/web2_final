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
?>

<!DOCTYPE html>
<html lang="sk">

<head>
    <title>Alica Ondreakova Page</title>
    <link rel="stylesheet" href="style.css">
    <meta name_t="viewport" content="width=device-width, initial-scale=1.0">
    <meta name_t="keywords" content="html, css">
    <meta name_t="author" content="Alica Ondreakova">
</head>

<ul>
  <li><a href="user.php">Profile</a></li>
  <li><a href="contacts.php">Contacts</a></li>
  <li><a href="?action=logout">Log out</a></li>

  <li style="float:right"><a class="active" href="#">Tilt of the aircraft</a></li>

  <li class="dropdown">
    <a href="javascript:void(0)" class="dropbtn">Simulators</a>
    <div class="dropdown-content">
      <a href="tlmenie.php">Car shock absorber</a>
      <a href="kyvadlo.php">Inverse pendulum</a>
      <a href="gulocka.php">Ball on a stick</a>
      <a href="lietadlo.php">Tilt of the aircraft</a>
    </div>
  </li>
</ul>

<body>
</body>
</html>