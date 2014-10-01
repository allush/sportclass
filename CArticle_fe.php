<?php
include_once 'common/CArticle.php';

class CArticle_fe extends CArticle {

    public function show() {
        ?>
        <ul class="article">
            <li class="title">
                <a href="index.php?p=article&id=<?php echo $this->id_article; ?>">
                    <?php echo $this->title; ?>
                </a>
            </li>
        </ul>
        <?php
    }

    public function showPreview() {
        include_once 'common/CHeading.php';
        ?>
        <div class="previewArticle">
            <div class="previewArticleDate">    
                <?php
                $date = date("d.m.Y", strtotime($this->date));
                if (date("d.m.Y", strtotime($this->date)) == date("d.m.Y", mktime()))
                    $date = "Сегодня";
                elseif (date("d.m.Y", mktime()) - date("d.m.Y", strtotime($this->date)) == 1)
                    $date = "Вчера";

                echo $date;
                ?>
            </div>
            <div class="previewArticleTitle"><a href="index.php?p=article&id=<?php echo $this->id_article; ?>"><?php echo $this->title; ?></a></div>

            <div class="previewArticleBody">

                <div class="previewArticleHeading" title="Рубрика"><img src="img/bracket.png" width="10"/> <?php echo $this->heading()->name(); ?></div>
                <?php
                $breakPos = strpos($this->body, "<p><!-- pagebreak --></p>");

                // если не найден разрыв - то не выводить превью основного контента
                if ($breakPos != false) {
                    $previewPart = substr($this->body, 0, $breakPos) . "</p>";
                    ?>  <div class="clearer"></div>
                        <?php echo $previewPart; ?>
                    <div class="clearer"></div>
                <?php } ?>

            </div>
            <div class="previewArticleMore"><a href="index.php?p=article&id=<?php echo $this->id_article; ?>">Читать дальше &rArr;</a></div>
        </div>
        <div class="delimiter" style="width: 100%;"></div>
        <?php
    }

    public function showDetail() {
        ?>
        <div class="heading"><?php echo $this->title; ?></div>
        <div><?php echo $this->body; ?></div>
        <?php
    }

}
?>
