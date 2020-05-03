<?php

define("LOGIN_SESSION", "login");

function isLogged() {
    return (isset($_SESSION[LOGIN_SESSION]));
}

function getUser() {
    if (isLogged()) {
    return ($_SESSION[LOGIN_SESSION]);
    }
    throw new Exception('User not logged in yet.');

}

function isRegistered() {
    if ( $_SESSION["accessType"] == 0) {
        return true;
    }
    else {
        return false;
    }

}

?>