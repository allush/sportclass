<?php

abstract class CPage {
    protected $p;

    public function __construct($p) {
        $this->p = $p;
        $fname = '_' . $p . '.php';
        if (file_exists($fname))
            require $fname;
    }

    // подгрузка нужный файлов
    abstract protected function _load();

    abstract protected function _head();

    public function head() {
        ?>
        <title><?php echo $this->title(); ?></title>

        <meta name="author"                 content="Alexey Lushnikov" />
        <meta name="Resource-Type"          content="Document" />        
        <meta name="keywords"               content="<?php echo $this->description(); ?>" lang="ru" />
        <meta name="description"            content="<?php echo $this->description(); ?>" lang="ru"/>

        <meta http-equiv="Content-Type"     content="text/html; charset=utf-8" />
        <meta http-equiv="pragma"           content="no-cache" />
        <meta http-equiv="content-language" content="ru" />
        <?php
        $this->_head();
    }

    abstract protected function _menu();

    public function menu() {
        return $this->_menu();
    }

    abstract protected function _title();

    public function title() {
        return $this->_title();
    }

    public function description() {
        if (function_exists('p_description'))
            return p_description();
    }

    public function breadcrumbs() {
        if (function_exists('p_breadcrumbs'))
            return p_breadcrumbs();
    }

    public function content() {
        if (function_exists('p_content'))
            return p_content();
    }

    abstract protected function _footer();

    public function footer() {
        return $this->_footer();
    }

}
?>
