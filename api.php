<?php

$cmd_start = "octave --eval '";
$cmd_end = "'";
$cmd = "";
$op = null;

if (isset($_GET['octave'])) {

    $program = $_GET['octave'];

    switch ($program) {
        case "pendulum":
        case "ball":
        case "vehicle":
        case "plane":
            if (isset($_GET['r'])) {
                $cmd = $cmd_start . 'r=' . $_GET['r'] . '; ' . $program . $cmd_end;
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

    echo json_encode($op);

}