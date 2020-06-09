<?php
require_once('phpconfig/keygen.php');
require_once('config.php');
require_once('libs/phpmailer/PHPMailer.php');
require_once('libs/phpmailer/SMTP.php');

define("PASSWD_RESET_EXPIRATION", "1 day");
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

function trySendPasswdResetEmail($email)
{
    global $conn, $root_url, $mail_host, $mail_port, $mail_smtp_secure, $mail_username, $mail_pass;

    $guid = genGUID();

    $query = "INSERT INTO PasswdResetRequests (guid, uid, created) "
        . "VALUES (?, (SELECT id FROM Registracia WHERE email = ?), ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Db error: " . $conn->error);
    }
    $stmt->bind_param("sss", $guid, $email, date("Y-m-d H:i:s"));

    if (!$stmt->execute()) {
        echo $stmt->error;
        return false;
    }

    $mail             = new PHPMailer();

    $body             = "Here is your password reset link: \n"
        . $root_url . "reset_passwd.php?guid=" . urlencode($guid) . "\n";
    //$body             = eregi_replace("[\]",'',$body);

    $mail->IsSMTP(); // telling the class to use SMTP
    //$mail->Host       = "ssl://smtp.gmail.com"; // SMTP server
    $mail->SMTPDebug  = 1;                     // enables SMTP debug information (for testing)
    // 1 = errors and messages
    // 2 = messages only
    $mail->SMTPAuth   = true;                  // enable SMTP authentication
    $mail->SMTPSecure = $mail_smtp_secure;                 // sets the prefix to the servier
    $mail->Host       = $mail_host;      // sets GMAIL as the SMTP server
    $mail->Port       = $mail_port;                   // set the SMTP port for the GMAIL server
    $mail->Username   = $mail_username;  // GMAIL username
    $mail->Password   = $mail_pass;            // GMAIL password

    $mail->setFrom($mail_username, "Admin");

    $mail->Subject    = "Password reset";

    $mail->isHTML(true);
    $mail->Body = $body;

    $mail->addAddress($email);


    if (!$mail->Send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
        return false;
    } else {
        return true;
    }

    return true;
}

function tryResetPasswd($resetRequestGuid, $newPasswd, $newSecret)
{
    global $conn;

    $query = "UPDATE Registracia "
        . "SET password=?, secret=? "
        . "WHERE id=(SELECT uid FROM PasswdResetRequests WHERE guid = ? AND created > ?)";
    $deleteQuery = "DELETE FROM PasswdResetRequests WHERE guid = ?";

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Db error: " . $conn->error);
    }
    $deleteStmt = $conn->prepare($deleteQuery);
    if (!$deleteStmt) {
        die("Db error: " . $conn->error);
    }

    $date = new DateTime();
    $date->modify("-". PASSWD_RESET_EXPIRATION);
    $stmt->bind_param("ssss", $newPasswd, $newSecret, $resetRequestGuid, $date->format("Y-m-d H:i:s"));
    if (!$stmt->execute()) {
        return false;
    }

    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Db error: " . $conn->error);
    }

    $deleteStmt->bind_param("s", $resetRequestGuid);
    $deleteStmt->execute();

    return true;
}
