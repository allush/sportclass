<?php

function wlog($code, $message) {
    $id_user = -1;
    if(isset($_SESSION['id_user']) && is_numeric($_SESSION['id_user']))
        $id_user = $_SESSION['id_user'];
//    $message = htmlentities($message,ENT_QUOTES);
    $message = htmlspecialchars($message, ENT_QUOTES);
    mysql_query("INSERT INTO `log`(`id_user`, `datetime`, `description`,`uri`,`status`) VALUES('$id_user', NOW(), '$message','".$_SERVER['REQUEST_URI']."','$code')") or die(mysql_error());

//    date_default_timezone_set('Europe/Moscow');
//    if ($code == -1) {
//
//        $fp = @fopen("log/" . date("Y-m-d") . '.log', "a+");
//
//        if ($fp) {
//            $time = date("G:i:s");
//            $msg = $time . " | " . $message . " | " . $_SERVER['REQUEST_URI'] . "<br>\n";
//            fwrite($fp, $msg);
//            fclose($fp);
//        }
//        return;
//    }
//
//    $user = new CUser($_SESSION['id_user']);
//    $fp = @fopen("log/" . $user->id_user() . "_" . date("Y-m-d") . '.log', "a+");
//
//    if ($fp) {
//        $time = date("G:i:s");
//        $msg = "";
//        if (!$code)
//            $msg .= "!!! ";
//
//        $msg .= $time . " | " . $user->last_name() . " " . $user->first_name() . " | " . $message . " | " . $_SERVER['REQUEST_URI'] . "<br>\n";
//        fwrite($fp, $msg);
//        fclose($fp);
//    }
}

?>
