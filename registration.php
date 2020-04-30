<?php
session_start();
require_once('config.php');

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
        $name = $conn->real_escape_string($_POST['name']);
        $surname = $conn->real_escape_string($_POST['surname']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = $conn->real_escape_string($_POST['password']);
        $login = $conn->real_escape_string($_POST['login']);
        $password = hash('sha256', $password);

        $stmt = $conn->prepare("SELECT * FROM Registracia WHERE login=?");
        if (!$stmt) {
            die("Db error: " . $conn->error);
        }
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) {
            die("Db error: " . $stmt->error);
        }

        $qresult = $stmt->get_result();

        if ($authenticator->verifyCode($secret, $_POST['code'], 2)) {

            if ($qresult->num_rows != 0) {
                echo "Login already exists";
            } else {

                $query = "INSERT INTO Registracia ( name, surname, email, login, password, secret) VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($query);
                if (!$stmt) {
                    die("Db error: " . $conn->error);
                }
                $stmt->bind_param("ssssss", $name, $surname, $email, $login, $password, $secret);

                if (!$stmt->execute()) {
                    die("Db error: " . $stmt->error);
                } else {
                    header("Location: index.php?registration=success");
                }
            }
        } else {
            echo "Bad authenticator code";
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
    <link rel="stylesheet" type="text/css" href="styles.css">
    <link rel="stylesheet" type="text/css" href="login.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="languages/dict.js"></script>
</head>

<body>
<button class="translate" id="en">En</button>
    <button class="translate" id="sk">Sk</button>


    <div class="topnav">
        <a href="about.php" class="lang" key="about-menu"></a>
        <a href="documentation.php" class="lang" key="doc-menu"></a>
        <a href="team.php" class="lang" key="team-menu"></a>
        <a style="float:right" href="index.php" class="lang" key="login-menu"></a>


        <a style="float:right" href="registration.php" class="lang act"  key="register-menu"></a>
    </div>

    <div id="center">

        <div class="wrapper fadeInDown">
            <div id="formContent">
                <!-- Tabs Titles -->
                <h2 class="active lang" id="reg" key="registration"></h2>

                <!-- Login Form -->
                <form action="registration.php" method="post" id="registration">

                    <input type="hidden" name="action" value="submit">
                    <input type='text' name="name" id="name" class="fadeIn first" class="lang" key="name_inp"
                    placeholder="name">
                    <input type="text" name="surname" id="surname" class="fadeIn first" placeholder="surname">
                    <input type="text" name="email" id="email" class="fadeIn second" placeholder="email">
                    <input type="text" name="login" id="login" class="fadeIn second" placeholder="login">
                    <input type="password" name="password" id="password" class="fadeIn third" placeholder="password">
                    <p class="fadeIn third lang"  key="auth_code_inp"></p> <!-- doesnt work lang -->
                    <p class="fadeIn fourth"><img src="<?php echo $qrCodeUrl; ?>"></p>
                    <input class="fadeIn fourth" type="text" name="code" id="code" placeholder="QR code">
                    <p id="errors"></p>
                    <!-- doesnt work lang  -->
                    <input type="submit" id="reg" class="fadeIn fifth lang" value="Register"  key="reg_button" onclick="validateForm() "></button>
                    <input type="checkbox" onclick="togglePassVisibility()" placeholder="Show password">
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
        var firstname = document.getElementById("name");
        var surname = document.getElementById("surname");
        var email = document.getElementById("email");
        var password = document.getElementById("password");
        var login = document.getElementById("login");
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
            if (firstname.value === '') {
                errors.innerHTML += "Name is required<br>";
            }
            if (surname.value === '') {
                errors.innerHTML += "Surname is required<br>";
            }
            if (email.value === '') {
                errors.innerHTML += "Email is required<br>";
            }
            if (login.value === '') {
                errors.innerHTML += "Login is required<br>";
            }
            if (password.value === '') {
                errors.innerHTML += "Password is required<br>";
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