<?php
include 'CNews.php';
include 'connect.php';
//while() Для получения всех записей
//{
//}
$ch = curl_init('http://api.vk.com/method/wall.get?owner_id=-15144618&count=50&filter="owner"');

curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$data = curl_exec($ch);
$obj = json_decode($data);

curl_close($ch);
$result = mysql_query("SELECT MAX (`id`) as max FROM `news`");
if ($result) {
    $row = mysql_fetch_array($result);
    $max = $row['max'];
    foreach ($obj->response as $post) { //для добавления новых новостей
        if ($post->{'id'} > $max) {
            $news = new CNews();
            $news->id = (int)$post->{'id'};
            $news->owner_id = (int)$post->{'owner_id'};
            $news->from_id = (int)$post->{'from_id'};
            $news->signer_id = (int)$post->{'signer_id'};
            $news->date = (int)$post->{'date'};
            $news->text = $post->{'text'};
            $news->save();
            $max = $post->{'id'};
        }

        echo '<br> XXX <br>' . $post->{'id'};
        echo $post->{'text'} . '<XXX --- <br>';

    }
} else {
    $max=0;

    foreach ($obj->response as $post) { //Если у нас нет новостей в базе, то добавляем все новости из запроса
        $news = new CNews();
        $news->id = (int)$post->{'id'};
        $news->owner_id = (int)$post->{'owner_id'};
        $news->from_id = (int)$post->{'from_id'};
        $news->signer_id = (int)$post->{'signer_id'};
        $news->date = (int)$post->{'date'};
        $news->text = $post->{'text'};
        $news->save();

        $max = $post->{'id'};
        echo '<br> XXX <br>' . $post->{'id'};
        echo $post->{'text'} . '<br> --- <br>';

    }
}





