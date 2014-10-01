<?php

include_once '../common/CArticle.php';

class CArticle_be extends CArticle { 

    public function __construct($id_article) {
        parent::__construct($id_article);
    }
    
    public static function create($title, $body, $id_heading) {

        $id_user = $_SESSION['id_user'];
        $title = htmlspecialchars($title,ENT_QUOTES);
        $body = htmlspecialchars($body,ENT_QUOTES);

        if (!mysql_query("INSERT INTO `article`(`title`,`body`, `id_heading`,`date`, `id_user`) VALUES('$title','$body', '$id_heading', NOW(), '$id_user')"))
            return false;

        $result = mysql_query("SELECT MAX(`id_article`) FROM `article`");
        $row = mysql_fetch_array($result);

        wlog(1, "Создана статья " . $title);
        return new CArticle_be($row[0]);
    }

    public function delete() {
        if (!$this->id_article)
            return false;

        if (!mysql_query("DELETE FROM `article` WHERE `id_article`='$this->id_article'"))
            return false;

        wlog(1, "Удалена статья " . $this->title);
        return true;
    }

    public function setTitle($title) {
        if ($this->title == $title)
            return;

        wlog(1, "Изменено название статьи с " . $this->title . " на " . $title);
        $this->title = $title;

        mysql_query("UPDATE `article` SET `title`='" . htmlspecialchars($this->title,ENT_QUOTES) . "' WHERE `id_article`='$this->id_article'");
    }
    
    
    public function setBody($body) {
        if ($this->body == $body)
            return;

        wlog(1, "Изменено содержание статьи " . $this->title);
        $this->body = $body;

        mysql_query("UPDATE `article` SET `body`='" . htmlspecialchars($this->body,ENT_QUOTES) . "' WHERE `id_article`='$this->id_article'");
    }

    public function setHeading($id_heading) {
        if ($this->id_heading == $id_heading)
            return;

        wlog(1, "Изменена рубрика статьи " . $this->title . " с " . $this->id_heading . " на " . $id_heading);
        $this->id_heading = $id_heading;

        mysql_query("UPDATE `article` SET `id_heading`='$this->id_heading' WHERE `id_article`='$this->id_article'");
    }

    
    
    public function show() {
        ?>
        <div class="list_item">
            <a href='index.php?p=article_edit&id_article=<?php echo $this->id_article; ?>'> <?php echo $this->title; ?></a> 
            <a class='small_grey right' href="article_delete.php?id_article=<?php echo $this->id_article; ?>" onclick="if(!confirm('Вы действительно хотите удалить статью ( <?php echo $this->title; ?> )?')) return false;">Удалить</a>
            <div class="clearer"></div>
        </div>
        <?php
    }

}
?>
