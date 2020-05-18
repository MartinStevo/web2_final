<?php

require_once('config.php');

function genGUID(){
    if (function_exists('com_create_guid')){
        return com_create_guid();
    }
    elseif (function_exists('random_int')) {
        return sprintf(
            '%04X%04X-%04X-%04X-%04X-%04X%04X%04X', 
            random_int(0, 65535), random_int(0, 65535), random_int(0, 65535), random_int(16384, 20479),
            random_int(32768, 49151), random_int(0, 65535), random_int(0, 65535), random_int(0, 65535));
    } else {
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
        $uuid= str_replace("{","",$uuid);
        $uuid= str_replace("}","",$uuid);

        return $uuid;
    }
}


?>

