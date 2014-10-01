<?php
include_once 'common/CVideo.php';

class CVideo_fe extends CVideo {

    public function __construct($code = 0) {
        parent::__construct($code);
    }

    public function show() {
        ?>
         <div class="video_item" onclick="location='index.php?p=video&code=<?php echo $this->code;?>'">
            <p class="thumbnail">
                <img class="play" src="img/play.png"/>
                <img src="<?php echo $this->thumbnail; ?>"/>
            </p>
            <p class="title"><a href="index.php?p=video&code=<?php echo $this->code;?>"><?php echo $this->title; ?></a></p>
            <p class="description"><?php echo $this->description; ?></p>
            <p class="clearer"></p>
        </div>
        <?php
    }

}
?>
