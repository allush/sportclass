<?php

session_start();
include '../common/connect.php';
include '../common/CUser.php';
include 'wlog.php';
$user = new CUser($_SESSION['id_user']);
if (!$user->has_permission(array(array("11" => array("write" => "1", "read" => "1"))))) {
    header("Location: index.php?p=permission_denied");
    return;
}

if(!isset($_POST['users']) || !is_array($_POST['users']))
    return;

if (!isset($_POST['body']))
    return;
if (!isset($_POST['subject']))
    return;

$users = $_POST['users'];
$body = $_POST['body'];
$subject = $_POST['subject'];

$subject = "=?utf-8?b?" . base64_encode($subject) . "?=";

$message = "<html>";
$message .= "<body>";
$message .= "Здравствуйте! <br><br>";
$message .= $body;
$message .= '<br>---------------------------------<br>';
$message .= 'С уважением, Елена Лушникова <br>';
$message .= 'Магазин Штуки <br>';
$message .= 'ул.Московская 37, 2 этаж <br>';
$message .= 'http://shtuki.pro <br>';
$message .= "</body>";
$message .= "</html>";

$header = "from: =?utf-8?b?" . base64_encode("Магазин Штуки") . "?= <info@shtuki.pro> \n";
$header.= "mime-version: 1.0 \n";
$header.= "content-type: text/html; charset=utf-8 \n";

foreach ($users as $id_user) {
    $user = new CUser($id_user);
    $to = $user->email();
    if (!$to)
        continue;
    mail($to, $subject, $message, $header);
}

header("Location: " . $_SERVER["HTTP_REFERER"]);
?>
