<?php
session_start();

if (!empty($_SESSION["login"])) {
    header("Location: user.php");
}
require_once('../../google/GoogleAuthenticator-1.0.1/PHPGangsta/GoogleAuthenticator.php');
$authenticator = new PHPGangsta_GoogleAuthenticator();
require_once("../../google/google-api-php-client-2.4.0/vendor/autoload.php");
$client = new Google_Client();
$client->setAuthConfig('../../google/credentials.json');
$client->setRedirectUri('http://wt221.fei.stuba.sk:8221/web2_final/index.php');
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
        echo "Registration successful! Now you can log in.";
    }
    if (isset($_GET["login"])) {
        if ($_GET["login"] == "none") {
            echo "Please log in.";
        } else if ($_GET["login"] == "loggedOut") {
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

    if (isset($_POST["action"])) {
        if ($_POST["action"] == "login") {
            $login = $conn->real_escape_string($_POST['login']);
            $password = "SELECT password, secret FROM Registracia WHERE login='$login'";
            $passwd = hash('sha256', ($_POST['password']));
            $googleAuthCode = $_POST["2fa"];
            $result = $conn->query($password);

            if ($row = $result->fetch_assoc()) {
                if ($passwd == $row["password"]) {
                    if ($authenticator->verifyCode($row["secret"], $googleAuthCode, 1)) {
                        $type = 0;
                        $date = date("Y-m-d H:i:s");
                        $query = "INSERT INTO Prihlasenia (time, login, accessType) VALUES ('$date','$login','$type')";
                        if (!$conn->query($query)) {
                            echo $conn->error;
                        } else {
                            $_SESSION["login"] = $login;
                            $_SESSION["accessType"] = $type;
                            header("Location: user.php");
                        }
                    } else {
                        echo "Invalid authenticator code";
                    }
                } else {
                    echo "Incorrect password";
                }
            } else {
                echo "Incorrect user name";
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
                    $query = "INSERT INTO Prihlasenia (time, login, accessType) VALUES ('$date','$login','$type')";
                    if (!$conn->query($query)) {
                        echo $conn->error;
                    } else {
                        $_SESSION["login"] = $login;
                        $_SESSION["accessType"] = $type;
                        header("Location: user.php");
                    }
                } else {
                    echo "LDAP bind failed";
                }
            }
        }
    } else if (!empty($_SESSION['googleToken']) && $client->getAccessToken()) {
        $gprofile = $service->userinfo->get();
        $login = $gprofile["email"];
        $type = 2;
        $date = date("Y-m-d H:i:s");
        $query = "INSERT INTO Prihlasenia (time, login, accessType) VALUES ('$date','$login','$type')";
        if (!$conn->query($query)) {
            echo $conn->error;
        } else {
            $_SESSION["login"] = $login;
            $_SESSION["accessType"] = $type;
            header("Location: user.php");
        }
    }
    ?>

</body>

</html>