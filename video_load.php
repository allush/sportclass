<?php

if ($_SERVER['REQUEST_METHOD'] != "POST")
    return;

if (!isset($_POST['from']))
    return;
$from = (int) $_POST['from'];

if (!isset($_POST['part']))
    return;
$part = (int) $_POST['part'];


include 'common/connect.php';
include 'CVideo_fe.php';
include 'common/CUser.php';

$query = "SELECT * FROM `video` ORDER BY `id_video` DESC LIMIT $from,$part";

$result = mysql_query($query) or die(mysql_error());
if (!$result)
    die("0");
if(!mysql_num_rows($result))
    die("0"); 

while ($row = mysql_fetch_array($result)) {
    $video = new CVideo_fe($row['code']);
    $video->show();
}
?>
