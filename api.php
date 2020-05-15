<?php

session_start();

$cmd_start = "octave --eval \"";
$cmd_end = "\"";
$cmd = "";
$op = null;

$disp["ball"] = 'disp(N*x(:,1)); disp (t); disp(x(:,3)); disp(x(size(x,1),:)); ';
$disp["pendulum"] = 'disp(x(:,1)); disp (t); disp(x(:,3)); disp(x(size(x,1),:)); ';
$disp["plane"] = "disp(N*x(:,1)); disp (t); disp(x(:,3)); disp(r*ones(size(t))*N-x*K'); ";
$disp["vehicle"] = 'disp(x(:,1)); disp (t); disp(x(:,3)); disp(x(size(x,1),:)); ';

$new_run["ball"] = '[y,t,x]=lsim(N*sys,r*ones(size(t)),t, x_temp); ';
$new_run["pendulum"] = '[y,t,x]=lsim(sys,r*ones(size(t)),t, x_temp); ';
$new_run["plane"] = '[y,t,x]=lsim(sys,r*ones(size(t)),t, x_temp); ';
$new_run["vehicle"] = '[y,t,x]=lsim(sys*[0;1],r*ones(size(t)),t, x_temp); ';

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
            }
            break;
        case "search":
            if (isset($_GET['exp'])) {
                $cmd = $cmd_start . $_GET['exp'] . $cmd_end;
            }
            break;
    }

    if ($cmd != null)
        $output = exec($cmd, $op, $rv);

    //var_dump($output);
    //var_dump($op);
    //var_dump($rv);

    if ($op != null) {
        $len = count($op) - 1;
        $y = array_slice($op, 0, $len / 3);
        $x = array_slice($op, $len / 3, $len / 3);
        $angle = array_slice($op, ($len / 3) * 2, $len / 3);
        $array = ["y" => $y, "x" => $x, "angle" => $angle];
        echo json_encode($array);
        // save x(size(x,1),:)
        $array_x = array_slice($op, $len);
        $_SESSION[$program] = $array_x;
    }
}