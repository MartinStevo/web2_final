<?php

session_start();
header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');
header("Connection: keep-alive");
header("Access-Control-Allow-Origin: *");
//header("acces-control-allow-credentials:true");

require_once('config.php');
require_once('statistics.php');

$cmd_start = "octave --eval \"";
$cmd_end = "\"";
$cmd = "";
$op = null;

$disp["ball"] = 'disp(N*x(:,1)); disp (t); disp(x(:,3)); disp(x(size(x,1),:)); ';
$disp["pendulum"] = 'disp(x(:,1)); disp (t); disp(x(:,3)); disp(x(size(x,1),:)); ';
$disp["plane"] = "disp(N*x(:,1)); disp (t); disp(r*ones(size(t))*N-x*K'); disp(x(size(x,1),:)); ";
$disp["vehicle"] = 'disp(x(:,1)); disp (t); disp(x(:,3)); disp(x(size(x,1),:)); ';

$new_run["ball"] = '[y,t,x]=lsim(N*sys,r*ones(size(t)),t, x_temp); ';
$new_run["pendulum"] = '[y,t,x]=lsim(sys,r*ones(size(t)),t, x_temp); ';
$new_run["plane"] = '[y,t,x]=lsim(sys,r*ones(size(t)),t, x_temp); ';
$new_run["vehicle"] = '[y,t,x]=lsim(sys*[0;1],r*ones(size(t)),t, x_temp); ';

$apiSession = $_SESSION["apikey"];
$apiWeb = $_GET["apikey"];
$error = "";
$query_v = "";

$apikey = "";
if (!empty($apiWeb))
    $apikey = $apiWeb;
elseif (!empty($apiSession))
    $apikey = $apiSession;

if (!empty($apikey) && check_if_api_exists($conn, $apikey)) {
    if (isset($_GET['octave'])) {

        $program = $_GET['octave'];

        switch ($program) {
            case "pendulum":
            case "ball":
            case "vehicle":
            case "plane":
                if (isset($_GET['r'])) {
                    if (empty($_SESSION[$program])) {
                        $cmd = $cmd_start . 'r=' . $_GET['r'] . '; ' . $program . '; ' . $disp[$program] . $cmd_end;

                    } else {
                        $array_x = implode(", ", $_SESSION[$program]);
                        $cmd = $cmd_start . 'r=' . $_GET['r'] . '; ' . $program . '; x_temp(1,:)=[' . $array_x . ']; ' . $new_run[$program] . $disp[$program] . $cmd_end;
                    }
                    $query_v = "r=" . $_GET['r'];
                } else {
                    $error = "r value not entered";
                }
                break;
            case "search":
                if (isset($_GET['exp'])) {
                    $cmd = $cmd_start . $_GET['exp'] . $cmd_end;
                    $query_v = "exp=" . $_GET['exp'];
                } else {
                    $error = "exp value not entered";
                }
                break;
        }

        if ($cmd != null)
            $output = exec($cmd, $op, $rv);
        else
            $error = "wrong api request";

        //var_dump($output);
        //var_dump($op);
        //var_dump($rv);

        if ($op != null) {
            if ($program != "search") {
                $len = count($op) - 1;
                $y = array_slice($op, 0, $len / 3);
                $x = array_slice($op, $len / 3, $len / 3);
                $angle = array_slice($op, ($len / 3) * 2, $len / 3);
                $array = ["y" => $y, "x" => $x, "angle" => $angle];
                echo json_encode($array);
                // save x(size(x,1),:)
                $array_x = array_slice($op, $len);
                $_SESSION[$program] = $array_x;
            } elseif ($program == "search") {
                echo json_encode($op);
            }
        }

        if ($rv == 1)
            $error = "octave syntax error";

            $query = "INSERT INTO Queries (date, success, error, apikey, query, form) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            die("Db error: " . $conn->error);
        }
        $stmt->bind_param('sdssss' , date("Y-m-d H:i:s"), $rv, $error, $apikey, $query_v, $program);
        if (!$stmt->execute()) {
            die("Db error: " . $stmt->error);
        }
    }
}