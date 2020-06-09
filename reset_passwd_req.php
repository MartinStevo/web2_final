<?php

session_start();
require_once('config.php');
require_once('phpconfig/emails.php');

if (isset($_POST["action"]) && $_POST["action"] == "resetpasswd") {
    if (trySendPasswdResetEmail($_POST["email"])) {
        $resetEmailResult = "success";
    } else {
        $resetEmailResult = "fail";
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
                <h2 class="active lang" id="reg" key="resetpasswd-button"></h2>

                <?php if (isset($resetEmailResult)) : ?>
                    <?php if ($resetEmailResult == "success") : ?>
                        <h3 class="lang" key="resetpasswd-sent"></h3>
                    <?php elseif ($resetEmailResult == "fail") : ?>
                        <h3 class="lang" key="resetpasswd-send-fail"></h3>
                    <?php endif; ?>
                <?php endif; ?>

                <!-- Login Form -->
                <form action="reset_passwd_req.php" method="post" id="resetpasswd">
                    <input type="hidden" name="action" value="resetpasswd">
                    <input type="text" name="email" id="email" class="fadeIn second lang-placeholder" key="email-holder" placeholder="email">
                    <p id="errors"></p>
                    <input type="submit" id="reset" class="fadeIn fifth lang-value" value="Reset password" key="resetpasswd-button" onclick="validateForm() "></button>

                </form>
            </div>
        </div>
    </div>



    <script>
        var email = document.getElementById("email");
        var resetPasswd = document.getElementById("resetpasswd");

        function validateForm() {
            errors.innerHTML = "";
            if (email.value === '') {
                errors.innerHTML += "Email is required<br>";
            }
            if (errors.innerHTML === "") {
                resetPasswd.submit();
            }
        }
    </script>

</body>

</html>