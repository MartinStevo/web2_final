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
    <title>Final</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="shortcut icon" type="image/png" href="other/favicon.ico" />
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="languages/dict.js"></script>
    <script type="text/javascript" src="settings.js"></script>

</head>


<body>
    <button class="translate" id="en">En</button>
    <button class="translate" id="sk">Sk</button>



    <div class="topnav">
        <a href="about.php" class="lang" key="about-menu"></a>
        <a href="documentation.php" class="lang" key="doc-menu"></a>
        <a href="team.php" class="lang" key="team-menu"></a>
        <a href="#" style="float:right" class="lang act" key="user-menu"></a>
        <a style="float:right" href="?action=logout" class="lang" key="signout-menu"></a>
    </div>

    <div class="hopnav">
            <a href="#" class="lang" key="stats-menu" id="statsBut"></a>
            <a href="#" class="lang" key="set-menu"  id="setBut"></a>
            <a href="team.php" class="lang" key="team-menu"></a>
            <a style="float:right"><?php echo $_SESSION["login"]; ?></a>
            <img style="float:right" src="other/profileicon.png" width="40px" height="40px">
            <a href="#" style="float:right" class="lang act" key="user-menu"></a>
        </div>
    <div id="user_container">
        
        <!-- <p>You are logged in as <?php echo $_SESSION["login"]; ?></p> -->
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
        <p style="margin-left:20px" id="global-login" class="stats">Global login statistics:</p>
        <table style="margin-left:20px;" id="show-global-login" class="stats">
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

        <div id="settings" style="margin-left:20px">

        <button style="margin-left:20px" id="showpromptpasswd" class="lang s" key="showpromptpasswd"></button>
            <div id="passwd-change">
                <p>Be a good lad</p>
            </div>
<br>
            <button style="margin-left:20px" id="showemailchange" class="lang" key="showemailchange" style="clear:both;"></button>
            <div id="email-change">
            </div>
<br>
           <button style="margin-left:20px" id="showkey" class="lang" key="showkey" >Show api key</button>
            <div id="api-key">
                <div style="text-align: left;margin-left:40px;">
                <input type="text" value="23921832138127fjkfashjda" id="myKey"> <img style="vertical-align: middle;" src="other/copytoclip.png" width="20px" height="20px" onclick="copytoClipboard()">
                </div>
            </div>

        </div>
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
        <p id="login-history" class="stats" style="margin-left:20px;">Your login history:</p>
        <table id="showloginhistory" class="stats" style="margin-left:20px;">
            <tr>
                <th>Time</th>
            </tr>
            <?php while ($row = $qresult->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo date("d. m. Y H:i:s", strtotime($row['time'])); ?></td>
                </tr>
            <?php endwhile; ?>
        </table>


    </div>
</body>

</html>