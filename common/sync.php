<?php
include 'CNews.php';
include 'connect.php';

$count = 2;
$offset = 0;

do {
    $ch = curl_init('http://api.vk.com/method/wall.get?owner_id=-15144618&offset=' . $offset . '&count=' . $count . '&filter="owner"');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $data = curl_exec($ch);
    $obj = json_decode($data);

    curl_close($ch);

    foreach ($obj->response as $post) {
        if (is_numeric($post)) {
            continue;
        }
        $result = mysql_query('SELECT * FROM `news` WHERE `id`=' . $post->{'id'});
        if (!$result) {
            break;
        }
//        if (mysql_num_rows($result) == 1) { //не дойдёт до следующих результатов
//            break;
//        }
        if (mysql_num_rows($result) == 0) {
            $news = new CNews();
            $news->id = (int)$post->{'id'};
            $news->owner_id = (int)$post->{'owner_id'};
            $news->from_id = (int)$post->{'from_id'};
            $news->signer_id = (int)$post->{'signer_id'};
            $news->date = (int)$post->{'date'};
            $news->text = $post->{'text'};

            if ($news->save()) {
                if (!empty($post->attachments)) foreach ($post->attachments as $attachment) {
                    if ((string)$attachment->{'type'} != 'photo') {
                        continue;
                    }
                    $photo_id = (int)$attachment->photo->{'pid'};
                    $photo_src = (string)$attachment->photo->{'src_big'};
                    mysql_query("INSERT  INTO `photo_news` (`id`, `path`, `news_id`) VALUES ('$photo_id','$photo_src','$news->id');");
                }
            }
        }
    }
    $offset += $count;
} while (count($obj->response) == $count + 1);





