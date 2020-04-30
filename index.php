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

if (isset($_POST["action"])) {

    if ($_POST["action"] == "login") {
        $passwd = hash('sha256', ($_POST['password']));
        $googleAuthCode = $_POST["2fa"];
        $login = $conn->real_escape_string($_POST['login']);

        //$count = $conn->prepare("SELECT COUNT(DISTINCT id) FROM Registracia");
        //$count = $count->get_result();
        //$res = 0;
        //if ($row = $count->fetch_assoc()) {
        //  $res += 1;
        //}
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

        $dn = 'ou=People, DC=stuba, DC=sk';
        $ldaprdn = "uid=$ldapuid, $dn";
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
<!DOCTYPE html>
<html lang="sk">

<head>
    <title>Final</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="login.css">


    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="languages/dict.js"></script>

    <script type="text/javascript" src="script.js"></script>
    <link rel="shortcut icon" type="image/png" href="other/favicon.ico" />
</head>

<body>

<button class="translate" id="en">En</button>
    <button class="translate" id="sk">Sk</button>

    <div class="background_1">
        <div class="topnav">
            <a href="about.php" class="lang" key="about-menu"></a>
            <a href="documentation.php" class="lang" key="doc-menu"></a>
            <a href="team.php" class="lang" key="team-menu"></a>
            <a style="float:right" class="act lang" key="login-menu" href="#about"></a>


            <a style="float:right" href="registration.php" class="lang" key="register-menu"></a>
        </div>





        <section>
            <div id="wrapp">
                
               <div id="first">
                   <h4 class="lang" key="h1-idx"></h4>
                   <h4 class="lang" key="h2-idx"></h4>
               </div>


                <div id="second">
                    <div class="wrapper fadeInDown">
                        <div id="formContent">
                            <!-- Tabs Titles -->

                            <h2 class="active lang" id="classical" key="normal_login"></h2>
                            <h2 class="inactive underlineHover lang" id="ldap" key="ldap_login"></h2>

                            <!-- Login Form -->
                            <form id="classical_form" action="index.php" method="post">

                                <input type="hidden" name="action" value="login">
                                <input type="text" id="login" name="login" class="fadeIn first" placeholder="login">
                                <input type="password" id="password" name="password" class="fadeIn second" placeholder="password">
                                <input type="text" id="2fa" name="2fa" placeholder="Google Authenticator Code" class="fadeIn third">
                                <input type="submit" class="fadeIn fourth" value="Log In">

                            </form>

                            <form id="ldap_form" action="index.php" method="post">

                                <input type="hidden" name="action" value="loginLdap">
                                <input type="text" id="login" name="login" placeholder="login" class="fadeIn first">
                                <input type="password" id="password" name="password" placeholder="password" class="fadeIn second">
                                <input type="submit" class="fadeIn third" value="Log In">


                            </form>

                            <!-- Remind Passowrd -->
                            <div id="formFooter" class="forget_passwd">
                                <a class="underlineHover lang" key="passwd_reset" href="#"></a>
                            </div>

                            <div id="formFooter" style="text-align:center">

                                <a class="underlineHover" href="<?php echo filter_var($authUrl, FILTER_SANITIZE_URL); ?>">
                                    <img src="btn_google_signin_light_normal_web.png">
                                </a>
                            </div>
                        </div>



                    </div>
                </div>


                <!--
    <form action="index.php" method="post">
       
            <legend>Login</legend>
            <input type="hidden" name="action" value="login">
            <p>Login:</p>
             <input type="text" id="login" name="login">
             <p>Password:</p>
            <input type="password" id="password" name="password">
            <p>Google Authenticator Code:</p>
            <input type="text" id="2fa" name="2fa">
            <input type="submit" style="color:green">
            <p><a href="registration.php">Register</a></p>
    </form>
        </div>
        <div id="second">
    <form action="index.php" method="post">
      
            <legend>Login STU LDAP</legend>
            <input type="hidden" name="action" value="loginLdap">
            <p>Login: <input type="text" id="login" name="login"></p>
            <p>Password: <input type="password" id="password" name="password"></p>
            <input type="submit">
       
    </form>
    !-->


            </div>

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


        </section>

    </div>



    <div id="center">
        <h4 class="lang" key="h3-idx"></h4>
        <img src="imageoctave.png" style="width:50px;height:50px">
        <h4 class="lang" key="h4-idx"></h4>
        <img src="stufei (1).png" style="width:100px;height:50px">


    </div>


    <footer>@Tomáš Klobučník @Alica Ondreáková @Jakub Rajčok @Martin Števo</footer>

</body>

</html>