<?php

session_start();

if (!empty($_SESSION["login"])) {
    header("Location: user.php");
}



require_once('config.php');
require_once($auth_php);
$authenticator = new PHPGangsta_GoogleAuthenticator();
require_once($gansta_path);
$client = new Google_Client();
$client->setAuthConfig($json_cred);

$client->setRedirectUri($red_uri);

$client->addScope("email");
$service = new Google_Service_Oauth2($client);
if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    $client->setAccessToken($token);
    $_SESSION['googleToken'] = $token;
}

if (!empty($_SESSION['googleToken'])) {
    if ($client->isAccessTokenExpired()) {
        unset($_SESSION['googleToken']);
    }
}
$authUrl = $client->createAuthUrl();

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
    <?php
    if (isset($_GET["registration"]) && $_GET["registration"] == "success") {
        echo "<p class='localized' key='login_message'></p>";
        echo "Registration successful! Now you can log in.";
    }
    if (isset($_GET["login"])) {
        if ($_GET["login"] == "none") {
            echo "<p class='localized' key='login_prompt'></p>";
            echo "Please log in.";
        } else if ($_GET["login"] == "loggedOut") {
            echo "<p class='localized' key='logout_message'></p>";
            echo "You were logged out.";
        }
    }
    ?>
    <form action="index.php" method="post">
        <fieldset>
            <legend>Login</legend>
            <input type="hidden" name="action" value="login">
            <p>Login: <input type="text" id="login" name="login"></p>
            <p>Password: <input type="password" id="password" name="password"></p>
            <p>Google Authenticator Code: <input type="text" id="2fa" name="2fa"></p>
            <input type="submit">
            <p><a href="registration.php">Register</a></p>
        </fieldset>

    </form>
    <form action="index.php" method="post">
        <fieldset>
            <legend>Login STU LDAP</legend>
            <input type="hidden" name="action" value="loginLdap">
            <p>Login: <input type="text" id="login" name="login"></p>
            <p>Password: <input type="password" id="password" name="password"></p>
            <input type="submit">
        </fieldset>
    </form>
    <p><a href="<?php echo filter_var($authUrl, FILTER_SANITIZE_URL); ?>">Login with Google</a></p>
    <?php

    if (isset($_POST["action"])) {
        if ($_POST["action"] == "login") {
            $passwd = hash('sha256', ($_POST['password']));
            $googleAuthCode = $_POST["2fa"];
            $login = $conn->real_escape_string($_POST['login']);

            $stmt = $conn->prepare("SELECT password, secret FROM Registracia WHERE login=?");

            if (!$stmt) {
                die("Db error: " . $conn->error);
            }
            $stmt->bind_param('s', $login);
            if (!$stmt->execute()) {
                die("Db error: " . $stmt->error);
            }
            $qresult = $stmt->get_result();

            if ($row = $qresult->fetch_assoc()) {
                if ($passwd == $row["password"]) {
                    if ($authenticator->verifyCode($row["secret"], $googleAuthCode, 1)) {
                        $type = 0;
                        $date = date("Y-m-d H:i:s");


                        $query  = "INSERT INTO Prihlasenia (time, login, accessType) VALUES (?, ?, ?)";
                        $stmt = $conn->prepare($query);
                        if (!$stmt) {
                            die("Db error: " . $conn->error);
                        }
                        $stmt->bind_param('sss', $date, $login, $type);
                        if (!$stmt->execute()) {
                            die("Db error: " . $stmt->error);
                        } else {
                            $_SESSION["login"] = $login;
                            $_SESSION["accessType"] = $type;
                            header("Location: user.php");
                        }
                    } else {
                        echo "<p class='localized' key='invalid_auth_code'></p>";
                        echo "Invalid authenticator code";
                    }
                } else {
                    echo "<p class='localized' key='invalid_passwd'></p>";

                    echo "Incorrect password";
                }
            } else {
                
                echo "<p class='localized' key='invalid_login'></p>";

                echo "Incorrect user name" + $login;
            }
        } else if ($_POST["action"] == "loginLdap") {
            $ldapuid = $_POST["login"];
            $ldappass = $_POST["password"];

            $dn  = 'ou=People, DC=stuba, DC=sk';
            $ldaprdn  = "uid=$ldapuid, $dn";
            $ldapconn = ldap_connect("ldap.stuba.sk")
                or die("Could not connect to LDAP server.");

            if ($ldapconn) {
                $set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
                // binding to ldap server
                $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

                // verify binding
                if ($ldapbind) {
                    $login = $ldapuid;
                    $type = 1;
                    $date = date("Y-m-d H:i:s");
                    $query = "INSERT INTO Prihlasenia (time, login, accessType) VALUES (?, ?, ?)";
                    $stmt = $conn->prepare($query);
                    if (!$stmt) {
                        die("Db error: " . $conn->error);
                    }
                    $stmt->bind_param('sss', $date, $login, $type);
                    if (!$stmt->execute()) {
                        die("Db error: " . $stmt->error);
                    } else {
                        $_SESSION["login"] = $login;
                        $_SESSION["accessType"] = $type;
                        header("Location: user.php");
                    }
                } else {
                    echo "<p class='localized' key='ldap_ref'></p>";
                    echo "LDAP bind failed";
                }
            }
        }
    } else if (!empty($_SESSION['googleToken']) && $client->getAccessToken()) {
        $gprofile = $service->userinfo->get();
        $login = $gprofile["email"];
        $type = 2;
        $date = date("Y-m-d H:i:s");


        $query = "INSERT INTO Prihlasenia (time, login, accessType) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Db error: " . $conn->error);
        }
        $stmt->bind_param('sss', $date, $login, $type);
        if (!$stmt->execute()) {
            die("Db error: " . $stmt->error);
        } else {
            $_SESSION["login"] = $login;
            $_SESSION["accessType"] = $type;
            header("Location: user.php");
        }
    }
    ?>

</body>

</html>