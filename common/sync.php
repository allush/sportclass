<?php
include 'CNews.php';
include 'connect.php';

$count = 50;
$offset = 0;

do {
    $ch = curl_init('http://api.vk.com/method/wall.get?owner_id=-15144618&offset=' . $offset . '&count=' . $count . '&filter="owner"');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $data = curl_exec($ch);
    $posts = json_decode($data);

    curl_close($ch);

    foreach ($posts->response as $post) {
        // если пришло число постов
        if (is_numeric($post)) {
            $result = mysql_query('SELECT COUNT(*) FROM `news`');
            if (!$result) {
                die(mysql_error()); // завершить выполнение скрипта с ошибкой
            }

            $newsCount = 0;
            $row = mysql_fetch_array($result);
            if ($row) {
                $newsCount = (int)$row[0];
            }

            if ($post == $newsCount) {
                exit('Нет новых записей');
            }

            continue;
        }

        $result = mysql_query('SELECT * FROM `news` WHERE `id`=' . $post->{'id'});
        if (!$result) {
            die(mysql_error()); // завершить выполнение скрипта с ошибкой
        }

        // если есть такой пост - пропускаем его
        if (mysql_num_rows($result) > 0) {
            continue;
        }

        $news = new CNews();
        $news->id = (int)$post->{'id'};
        $news->owner_id = (int)$post->{'owner_id'};
        $news->from_id = (int)$post->{'from_id'};
        $news->signer_id = (int)$post->{'signer_id'};
        $news->date = (int)$post->{'date'};
        $news->text = (string)$post->{'text'};
        if (strlen($news->text) == 0) {
            $news->text = (string)$post->{'copy_text'};
        }

        if ($news->save()) {
            foreach ($post->attachments as $attachment) {
                if ((string)$attachment->{'type'} != 'photo') {
                    continue;
                }
                $photo_id = (int)$attachment->photo->{'pid'};
                $photo_src = (string)$attachment->photo->{'src_big'};
                $photo_text = (string)$attachment->photo->{'text'};
                mysql_query("INSERT  INTO `photo_news` (`id`, `path`, `news_id`, `text`) VALUES ('$photo_id', '$photo_src', '$news->id', '$photo_text');");
            }
        }
    }
    $offset += $count;
} while ((count($posts->response) - 1) == $count);

echo "Синхронизация выполнена";





