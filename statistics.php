   <?php

    require_once('phpconfig/keygen.php');
    require_once('phpconfig/userses.php');
    require_once('config.php');


    ?>


   <?php



    function insert_page($conn, $page, $login)
    {
        $stmt = $conn->prepare("INSERT INTO Statistics(page,login) VALUES ( ?, ?)");
        if (!$stmt) {
            die("Db error: " . $conn->error);
        }
        $stmt->bind_param('ss', $page, $login);
        if (!$stmt->execute()) {
            die("Db error: " . $stmt->error);
        }

        $stmt->close();
    }

    function insert_apikey($conn, $login)
    {
        $stmt = $conn->prepare("INSERT INTO User(login,apikey) VALUES ( ?, ?)");
        if (!$stmt) {
            die("Db error: " . $conn->error);
        }
        $key = genGUID();

        $stmt->bind_param('ss', $login, $key);
        if (!$stmt->execute()) {
            die("Db error: " . $stmt->error);
        }
        $stmt->close();
    }

    function check_if_user_has_key($conn, $login)
    {
        $stmt = $conn->prepare("SELECT apikey FROM User WHERE login=?");
        if (!$stmt) {
            die("Db error: " . $conn->error);
        }

        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) {
            die("Db error: " . $stmt->error);
        }
        $qresult = $stmt->get_result();

        if ($row = $qresult->fetch_assoc()) {
            return true;
        } else {
            return false;
        }

        $stmt->close();
    }

   function check_if_api_exists($conn, $apikey)
   {
       $stmt = $conn->prepare("SELECT apikey FROM User WHERE apikey=?");
       if (!$stmt) {
           die("Db error: " . $conn->error);
       }

       $stmt->bind_param('s', $apikey);
       if (!$stmt->execute()) {
           die("Db error: " . $stmt->error);
       }
       $qresult = $stmt->get_result();

       if ($row = $qresult->fetch_assoc()) {
           return true;
       } else {
           return false;
       }

       $stmt->close();
   }

    function get_api_key($conn, $login)
    {
        $stmt = $conn->prepare("SELECT apikey FROM User WHERE login=?");
        if (!$stmt) {
            die("Db error: " . $conn->error);
        }
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) {
            die("Db error: " . $stmt->error);
        }
        $qresult = $stmt->get_result();

        if ($row = $qresult->fetch_assoc()) {
            return $row['apikey'];
        } else {
            return false;
        }

        $stmt->close();
    }

    function regenerate_api_key($conn, $login)
    {
        $key = genGUID();
        $stmt = $conn->prepare("UPDATE User SET apikey= ? WHERE login = ?");
        if (!$stmt) {
            die("Db error: " . $conn->error);
        }
        $stmt->bind_param('ss', $key,$login);
        if (!$stmt->execute()) {
            die("Db error: " . $stmt->error);
        }


        $stmt->close();
    }

    function check_password($conn, $login, $password)
    {
        $stmt = $conn->prepare("SELECT password FROM Registracia WHERE login = ?");
        $passwd = hash('sha256', $password);
        if (!$stmt) {
            die("Db error: " . $conn->error);
        }
        $stmt->bind_param('s', $login);
        if (!$stmt->execute()) {
            die("Db error: " . $stmt->error);
        }

        $qresult = $stmt->get_result();
        if ($row = $qresult->fetch_assoc()) {
            if ($row['password'] == $passwd) {
                return true;
            }
        } else {
            return false;
        }


        $stmt->close();
    }

    function change_password($conn, $login, $password)
    {

        $passwd = hash('sha256', $password);
        $stmt = $conn->prepare("UPDATE Registracia SET password= ? WHERE login = ?");
        if (!$stmt) {
            die("Db error: " . $conn->error);
        }
        $stmt->bind_param('ss', $passwd, $login);
        if (!$stmt->execute()) {
            die("Db error: " . $stmt->error);
        }


        $stmt->close();
    }

    ?>