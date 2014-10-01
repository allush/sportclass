<?php
$dblocation = "localhost";
$dbname = "sportclass";
$dbuser = "sportclass";
$dbpasswd = "3V9e8S5z";

$dbcnx = mysql_connect($dblocation,$dbuser,$dbpasswd);
if (!@mysql_select_db($dbname, $dbcnx))exit();
mysql_set_charset("utf8");
?>
