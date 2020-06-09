<?php
session_start();
require_once('config.php');;
require_once('phpconfig/emails.php');

require_once($auth_php);

$authenticator = new PHPGangsta_GoogleAuthenticator();

if (empty($_SESSION['authenticatorSecret'])) {
    $_SESSION['authenticatorSecret'] = $authenticator->createSecret();
}
$secret = $_SESSION['authenticatorSecret'];

$website = $red_uri;

$title = 'Final project';
$qrCodeUrl = $authenticator->getQRCodeGoogleUrl($title, $secret, $website);

require_once('config.php');

if (isset($_POST['action'])) {

    if ($_POST['action'] == 'submit') {
        $newpassword = hash('sha256', $_POST['newpassword']);

        if ($authenticator->verifyCode($secret, $_POST['code'], 2)) {
            if (tryResetPasswd($_GET['guid'], $newpassword, $secret)) {
                $resetPasswdStatus = "success";
            } else {
                $resetPasswdStatus = "fail";
            }
        } else {
            $resetPasswdStatus = "badAuthenticatorCode";
        }
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
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <link rel="stylesheet" type="text/css" href="css/login.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="js/dict.js"></script>
</head>

<body>


    <?php require_once('widgets/nav.php'); ?>


    <div id="center">

        <div class="wrapper fadeInDown">
            <div id="formContent">
                <!-- Tabs Titles -->
                <h2 class="active lang" id="reg" key="registration"></h2>

                <?php if (isset($resetPasswdStatus)) : ?>
                    <?php if ($resetPasswdStatus == "success") : ?>
                        <h3 class="lang" key="resetpasswd-done"></h3>
                    <?php elseif ($resetPasswdStatus == "fail") : ?>
                        <h3 class="lang" key="resetpasswd-fail"></h3>
                    <?php elseif ($resetPasswdStatus == "badAuthenticatorCode") : ?>
                        <h3 class="lang" key="bad-authenticator-code"></h3>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Login Form -->
                <form action="reset_passwd.php?guid=<?php echo urlencode($_GET["guid"]); ?>" method="post" id="registration">

                    <input type="hidden" name="action" value="submit">
                    <input type="password" name="new-password" id="new-password" class="fadeIn third lang-placeholder" key="new-passwd-holder" placeholder="new password">
                    <p class="fadeIn third lang"  key="auth_code_inp"></p> <!-- doesnt work lang -->
                    <p class="fadeIn fourth"><img src="<?php echo $qrCodeUrl; ?>"></p>
                    <input class="fadeIn fourth lang-placeholder" key="qr-holder" type="text" name="code" id="code" placeholder="QR code">
                    <p id="errors"></p>
                    <!-- doesnt work lang  -->
                    <input type="submit" id="reg" class="fadeIn fifth lang-value"  value="Register"  key="reg_button" onclick="validateForm() "></button>
                    <input type="checkbox" onclick="togglePassVisibility()" placeholder="Show passwords">
                </form>
            </div>
        </div>


        <!--
    <form action="registration.php" method="post" id="registration">
      
            
        

        <input type="checkbox" onclick="togglePassVisibility()">Show Password
    </form>
-->
    </div>



    <script>
        var newpassword = document.getElementById("new-password");
        var code = document.getElementById("code");
        var errors = document.getElementById("errors");
        var registration = document.getElementById("registration");

        function togglePassVisibility() {
            if (password.type === "password") {
                password.type = "text";
            } else {
                password.type = "password";
            }
        }

        function validateForm() {
            errors.innerHTML = "";
            if (newpassword.value === '') {
                errors.innerHTML += "New password is required<br>";
            }
            if (code.value === '') {
                errors.innerHTML += "Authenticator code is required<br>";
            }
            if (errors.innerHTML === "") {
                registration.submit();
            }
        }
    </script>

</body>

</html>