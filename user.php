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

    <meta name_t="viewport" content="width=device-width, initial-scale=1.0">
    <meta name_t="keywords" content="html, css">
    <meta name_t="author" content="Alica Ondreakova">
</head>


<body>
    <p>You are logged in as <?php echo $_SESSION["login"]; ?>. <a href="?action=logout">Log out</a></p>
    <?php

    $servername_t = "localhost";
    $username_t = "xondreakova";
    $password = "h7g3Mn9k";
    $dbname_t = "autentifikacia";
    $conn = new mysqli($servername_t, $username_t, $password, $dbname_t);
    mysqli_set_charset($conn, "utf8");

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $statsResult = $conn->query("SELECT accessType, COUNT(*) as c FROM Prihlasenia GROUP BY accessType ORDER BY accessType ASC");
    $registeredCount = 0;
    $ldapCount = 0;
    $googleCount = 0;
    while ($row = $statsResult->fetch_assoc()) {
        if ($row["accessType"] == 0) {
            $registeredCount = $row["c"];
        } else if ($row["accessType"] == 1) {
            $ldapCount = $row["c"];
        } else if ($row["accessType"] == 2) {
            $googleCount = $row["c"];
        }
    }
    ?>
    <p>Global login statistics:</p>
    <table>
        <tr>
            <th>Registered:</th>
            <th>LDAP:</th>
            <th>Google:</th>
        </tr>
        <tr>
            <td><?php echo $registeredCount; ?></td>
            <td><?php echo $ldapCount; ?></td>
            <td><?php echo $googleCount; ?></td>
        </tr>
    </table>
    <?php
    $login = $_SESSION["login"];
    $accessType = $_SESSION["accessType"];
    $loginsResult = $conn->query("SELECT time FROM Prihlasenia WHERE login='$login' AND accessType='$accessType' ORDER BY time DESC");
    if (!$loginsResult) {
        echo $conn->error;
    }
    ?>
    <p>Your login history:</p>
    <table>
        <tr>
            <th>Time</th>
        </tr>
        <?php while ($row = $loginsResult->fetch_assoc()) : ?>
            <tr><td><?php echo date("d. m. Y H:i:s", strtotime($row['time'])); ?></td></tr>
        <?php endwhile; ?>
    </table>
</body>

</html>