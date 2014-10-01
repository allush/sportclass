<?php
include '../common/CPage.php';

class CPage_be extends CPage {

    public function __construct($p) {
        parent::__construct($p);
        $this->_load();
        $this->check_permission();
    }

    protected function _load() {
        include '../common/connect.php';
        include '../common/CCatalog.php';
        include '../common/CUser.php';
        include 'CProduct_be.php';
        include 'wlog.php';
        date_default_timezone_set("Europe/Moscow");
    }

    //Проверка прав доступа пользователя к странице
    private function check_permission() {
        if (!isset($_SESSION['id_user'])) {
            header("Location: _auth.php");
            return;
        }

        // Проверка требований, предъявляемых к пользователю для работы со страницей
        if (function_exists('p_permission')) {
            $permissions = p_permission();

            $user = new CUser($_SESSION['id_user']);
            // если нет прав, то на страницу оповещения
            if (!$user->has_permission($permissions)) {
                header("Location: index.php?p=permission_denied");
                return;
            }
        }
    }

    protected function _title() {
        if (function_exists('p_title'))
            return "Админка :: " . p_title();
    }

    protected function _head() {
        ?>
        <link rel="icon" type="image/x-icon" href="img/icon.png" />

        <meta http-equiv="Cache-Control" content="no-cache">

        <link rel="stylesheet" type="text/css" href="css/general.css" />
        <link rel="stylesheet" type="text/css" href="css/income.css" />
        <link rel="stylesheet" type="text/css" href="css/CProduct.css" />
        <link rel="stylesheet" type="text/css" href="css/CTurnover.css" />
        <link rel="stylesheet" type="text/css" href="css/CVideo.css" />
        <link rel="stylesheet" type="text/css" href="css/CUser.css" />
        <link rel="stylesheet" type="text/css" href="css/log.css" />

        <script type="text/javascript" src="js/jquery.js"></script>
        <script type="text/javascript" src="js/general.js"></script>

        <link rel="stylesheet" type="text/css" href="shadowbox/shadowbox.css">
        <script type="text/javascript" src="shadowbox/shadowbox.js"></script>
        <script type="text/javascript">
            Shadowbox.init();
        </script>
        
        <?php
    }

    protected function _menu() {
        $user = new CUser($_SESSION['id_user']);
        ?>
        <ul>
            <?php if ($user->has_permission(array(array("9" => array("write" => "0", "read" => "1"))), 0)) { ?>
                <li id="my_profile" title="<?php $this->user(); ?>" >     
                    <a href="index.php?p=my_profile"><img height="24" src="img/man.png" /></a>   
                </li>
            <?php } ?>

            
            <?php
            if ($user->has_permission(array(array("1" => array("write" => "0", "read" => "1"))), 0)) {
                ?>
                <li id="security" title="Безопасность">     
                    <a href="index.php?p=security"><img height="24" src="img/padlock.png" /></a>   
                </li>
            <?php } ?>
                
                 <?php
            if ($user->has_permission(array(array("14" => array("write" => "0", "read" => "1"))), 0)) {
                ?>
                <li id="heading" title="Рубрики"">     
                    <a href="index.php?p=heading"><img height="24" src="img/heading.png" /></a>   
                </li>
            <?php } ?>
                
            <?php
            if ($user->has_permission(array(array("13" => array("write" => "0", "read" => "1"))), 0)) {
                ?>
                <li id="articles" title="Статьи">     
                    <a href="index.php?p=articles"><img height="24" src="img/article.png" /></a>   
                </li>
            <?php } ?>
                                
            <?php if ($user->has_permission(array(array("8" => array("write" => "0", "read" => "1"))), 0)) { ?>
                <li id="video"  title="Видео">  
                    <a href="index.php?p=video"><img height="24" src="img/video.png"/></a>
                </li>
            <?php } ?>
                
            <?php if ($user->has_permission(array(array("2" => array("write" => "0", "read" => "1"))), 0)) { ?>
                <li id="catalog" title="Каталоги">     
                    <a href="index.php?p=catalog"><img height="24" src="img/folder.png"/></a>   
                </li>
            <?php } ?>

            <?php if ($user->has_permission(array(array("3" => array("write" => "0", "read" => "1"))), 0)) { ?>
                <li id="product" title="Товары">  
                    <a href="index.php?p=product"><img height="24" src="img/shoppingcart.png"/></a>
                </li>
            <?php } ?> 
            
            <?php if ($user->has_permission(array(array("7" => array("write" => "0", "read" => "1"))), 0)) { ?>
                <li id="settings" title="Настройки">  
                    <a href="index.php?p=settings"><img height="24" src="img/settings.png" /></a>
                </li>
            <?php } ?>
        </ul>

        <?php
        if (isset($_GET['p'])) {
            ?>
            <script type="text/javascript">
                $("#<?php echo $_GET['p']; ?>").addClass('active');
            </script>
            <?php
        } else {
            ?>
            <script type="text/javascript">
                $("#my_profile").addClass('active');
            </script>
            <?php
        }
    }

    public function dark_block() {
        if (function_exists('p_dark_block'))
            return p_dark_block();
    }

    public function navigation() {
        if (function_exists('p_navigation'))
            return p_navigation();
    }

    public function user() {
        $user = new CUser($_SESSION['id_user']);
        echo $user->first_name() . " " . $user->last_name();
    }

    protected function _footer() {
        ;
    }

}
?>
