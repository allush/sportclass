<?php

class CComment {

    public $title;
    public $content;
    public $author_name;
    public $author_uri;

    public function show() {
        ?>
        <div class='comment_item'>
            <div class="comment_content"><?php echo $this->content; ?></div>
            <div class="author">
                <a href="<?php echo $this->author_uri; ?>"><?php echo $this->author_name; ?></a>
            </div>
        </div>
        <?php
    }

}

abstract class CVideo {

    protected $title = false;
    protected $id_heading = false;
    protected $code = false;
    protected $thumbnail = false;
    protected $description = false;
    protected $comments = array();
    private $client_id = "shtuki";
    private $developer_key = "AI39si4kN1nqKyUBSXVNnMkSCupa4AZQyKKAyaDDHFUatVlWhs0_f3QdA0mNZlZVoTVYB4aLKPKHMc0xGIKMLS7tuFbu4GAJuw";

    public function __construct($code = 0) {

        if (!$code || strlen($code) != 11)
            return 0;

        $this->code = $code;

        $result = mysql_query("SELECT * FROM `video` WHERE `code`='$this->code'");
        $row = mysql_fetch_array($result);

        $this->id_heading = $row['id_heading'];

        $this->init();
    }

    public static function create($code, $id_heading) {

        if (!mysql_query("INSERT INTO `video`(`code`,`id_heading`) VALUES ('$code','$id_heading')"))
            return 0;
//
//        $this->code = $code;
//        $this->id_heading = $id_heading;
//
//        $this->init();
    }

    private function init() {
        $url = "http://gdata.youtube.com/feeds/api/videos/" . $this->code;
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $page = curl_exec($c);
        curl_close($c);

		if($page == 'Video not found')
		    return;
		
        $data = simplexml_load_string($page);       //Интерпретирует XML-файл в объект
        $this->title = $data->title;
        $this->thumbnail = $data->children("media", true)->group->thumbnail[0]->attributes()->url;
        $this->description = $data->content;

        // Получаем комментарии
        $url = "http://gdata.youtube.com/feeds/api/videos/" . $this->code . "/comments";
        $c = curl_init($url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        $page = curl_exec($c);
        curl_close($c);

        $data = simplexml_load_string($page);       //Интерпретирует XML-файл в объект 
        foreach ($data->entry as $comment) {
            $com = new CComment();
            $com->title = $comment->title;
            $com->content = $comment->content;
            $com->author_name = $comment->author->name;
            $com->author_uri = $comment->author->uri;

            $this->comments[] = $com;
        }
    }

    public function comments() {
        return $this->comments;
    }

    public function comment_add($comment) {
        $data = '<?xml version="1.0" encoding="UTF-8"?>
                <entry xmlns="http://www.w3.org/2005/Atom"
                    xmlns:yt="http://gdata.youtube.com/schemas/2007">
                    <content>' . $comment . '</content>
                </entry>';

        $request = "";
        $fp = fsockopen("gdata.youtube.com", 80, $errno, $errstr, 20);
        if ($fp) {
            $request .= "POST /feeds/api/videos/" . $this->code . "/comments HTTP/1.1\r\n";
            $request .= "Host: gdata.youtube.com\r\n";
            $request .= "Content-Type: application/atom+xml\r\n";
            $request .= "Content-Length: " . strlen($data) . "\r\n";
            $request .= "Authorization: AuthSub token='AUTHORIZATION_TOKEN'\r\n";
            $request .= "GData-Version: 2";
            $request .= "X-GData-Client: " . $this->client_id . "\r\n";
            $request .= "X-GData-Key: key=" . $this->developer_key . "\r\n";
            $request .= "\r\n";
            $request .= $data . "\r\n";

            socket_set_timeout($fp, 10);

            fputs($fp, $request, strlen($request));
        }
    }

    public function title() {
        return $this->title;
    }

    public function heading() {
        include_once 'CHeading.php';
        return new CHeading($this->id_heading);
    }

    public function setHeadig($id_heading) {
        if ($this->id_heading == $id_heading)
            return true;

        if (!mysql_query("UPDATE `video` SET `id_heading`='$id_heading' WHERE `code`='$this->code'"))
            return false;

        wlog(1, "Видео " . $this->title . " было перемещено из рубрики " . $this->id_heading . " в рубрику " . $id_heading);
        $this->id_heading = $id_heading;

        return true;
    }

    public function code() {
        return $this->code;
    }

    public function set_code($code) {
        if ($this->code == $code)
            return 0;

        $result = mysql_query("UPDATE `video` SET `code` = '$code' WHERE `code` = '$this->code'");
        if (!$result)
            return 0;

        $this->code = $code ;
    }

    public function delete() {
        if (!$this->code)
            return 0;
        if (!mysql_query("DELETE FROM `video` WHERE `code` = '$this->code'"))
            return -1;
        return 1;
    }

    abstract public function show();
}
?>
