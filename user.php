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

<div id="head">

<ul>
  <li><a href="#profile" class="active">Profile</a></li>
  <li><a href="contacts.php">Contacts</a></li>
  <li><a href="?action=logout">Log out</a></li>

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
</div>

<body>
    <p>You are logged in as <?php echo $_SESSION["login"]; ?>. <a href="?action=logout">Log out</a></p>
    <?php
    require_once('config.php');


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

    $stmt = $conn->prepare("SELECT time FROM Prihlasenia WHERE login=? AND accessType=? ORDER BY time DESC");
    if (!$stmt) {
        die("Db error: " . $conn->error);
    }
    $stmt->bind_param('ss', $login, $accessType);
    if (!$stmt->execute()) {
        die("Db error: " . $stmt->error);
    }

    $qresult = $stmt->get_result();
    if (!$qresult) {
        echo $conn->error;
    }
    ?>
    <p>Your login history:</p>
    <table>
        <tr>
            <th>Time</th>
        </tr>
        <?php while ($row = $qresult->fetch_assoc()) : ?>
            <tr>
                <td><?php echo date("d. m. Y H:i:s", strtotime($row['time'])); ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>