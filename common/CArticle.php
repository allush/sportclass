<?php

abstract class CArticle {

    protected $id_article = false;
    protected $title = false;
    protected $body = false;
    protected $author = false;
    protected $date = false;
    protected $id_heading = false;

    public function __construct($id_article) {
        if (!$id_article)
            return;

        $this->id_article = $id_article;

        $result = mysql_query("SELECT * FROM `article` WHERE `id_article`='$this->id_article'");
        if (!$result)
            return;

        $row = mysql_fetch_array($result);

        $this->title = htmlspecialchars_decode($row['title'],ENT_QUOTES);
        $this->body = htmlspecialchars_decode($row['body'],ENT_QUOTES);
        //$this->author = $row['author'];
        $this->date = $row['date'];
        $this->id_heading = $row['id_heading'];
    }

    public function id(){
        return $this->id_article;
    }


    public function title() {
        return $this->title;
    }


    public function body() {
        return $this->body;
    }

    public function idHeading() {
        return $this->id_heading;
    }
    
    public function heading(){
        return new CHeading($this->id_heading);
    }

    abstract public function show();

}
?>
