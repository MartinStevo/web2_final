<!DOCTYPE html>
<html lang="sk">
<head>
    <title>Alica Ondreakova Page</title>

    <meta name_t="viewport" content="width=device-width, initial-scale=1.0">
    <meta name_t="keywords" content="html, css">
    <meta name_t="author" content="Alica Ondreakova">


</head>
<?php
session_start();
require_once('../../google/GoogleAuthenticator-1.0.1/PHPGangsta/GoogleAuthenticator.php');
$authenticator = new PHPGangsta_GoogleAuthenticator();

if (empty($_SESSION['authenticatorSecret'])) {
    $_SESSION['authenticatorSecret'] = $authenticator->createSecret();
}
$secret = $_SESSION['authenticatorSecret'];

$website = 'http://wt221.fei.stuba.sk:8221/web2_final/index.php';
$title = 'Zadanie 3';
$qrCodeUrl = $authenticator->getQRCodeGoogleUrl($title, $secret,$website);
?>
<body>

    <form action="registration.php" method="post" id="registration">
        <fieldset>
            <input type="hidden" name="action" value="submit">
            <p>Name:<input type='text' name="name" id="name"></p>
            <p>Surname: <input type="text" name="surname" id="surname" /></p>
            <p>email: <input type="text" name="email" id="email" /></p>
            <p>Login: <input type="text" name="login" id="login" /></p>
            <p>Password: <input type="password" name="password" id="password" /></p>
            <p>Google Authenticator QR code: </p>
            <p><img src="<?php echo $qrCodeUrl; ?>"></p>
            <p>Authenticator code: <input type="text" name="code" id="code" /></p>
            <p id="errors"></p>
            <button type="button" onclick="validateForm() ">Submit</button>
        </fieldset>

        <input type="checkbox" onclick="myFunction()">Show Password
    </form>
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

    if (isset($_POST['action'])) {

        if ($_POST['action'] == 'submit') {
            $name = $conn->real_escape_string($_POST['name']);
            $surname = $conn->real_escape_string($_POST['surname']);
            $email = $conn->real_escape_string($_POST['email']);
            $password = $conn->real_escape_string($_POST['password']);
            $login = $conn->real_escape_string($_POST['login']);
            $password = hash('sha256', $password);
            $existing = $conn->query("SELECT * FROM Registracia WHERE login='$login'");
            if ($authenticator->verifyCode($secret, $_POST['code'], 2)) {

                if ($existing->num_rows != 0) {
                    echo "Login already exists";
                } else {

                    $query = "INSERT INTO Registracia ( name, surname, email, login, password, secret) VALUES ('$name','$surname','$email','$login','$password','$secret')";

                    if (!$conn->query(($query))) {
                        echo "Registration failed";
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