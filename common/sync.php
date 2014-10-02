<?php
$ch = curl_init('http://api.vk.com/method/wall.get?owner_id=-15144618&count=50&filter="owner"');
$news = new News();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$data  = curl_exec($ch);
$obj=json_decode($data);

curl_close($ch);


foreach($obj->response as $post){

$news->text=$post->{'text'};
    echo $post->{'text'}.'<br> --- <br>';
}



