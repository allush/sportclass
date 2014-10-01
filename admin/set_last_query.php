<?php

function set_last_query($query){
    $query = base64_encode($query);
    return mysql_query("UPDATE `last_query` SET `query`='$query'");
}

?>
