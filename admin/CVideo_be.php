<?php
include_once '../common/CVideo.php';

class CVideo_be extends CVideo {

    //put your code here
    public function __construct($id_video = 0) {
        parent::__construct($id_video);
    }

    public function show() {
        ?>
        <div class="video_item">
            <p class="thumbnail"><img width="200" src="<?php echo $this->thumbnail; ?>"/></p>
            <p class="title"><?php echo $this->title; ?></p>
            <p class="heading"><?php echo $this->heading()->name(); ?></p>

            <p class="clearer"></p>
            <p class="action">
                <a onclick="if(!confirm('Вы действительно хотите удалить видео?')) return false;"
                   class="small_grey" 
                   href="video_delete.php?code=<?php echo $this->code; ?>">Удалить</a>
            </p>
        </div>
        <?php
    }

}
?>
