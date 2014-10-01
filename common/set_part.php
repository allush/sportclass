<?php

$part = $_POST['part'];
setcookie('part', $part,0,"/");
setcookie('part_admin', $part,0,"/admin");
header("Location: " . $_SERVER['HTTP_REFERER']);
?>
