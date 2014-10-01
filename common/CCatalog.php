<?php

class CCatalog {

    private $id_catalog = false;
    private $name = false;
    private $description = false;
    private $cover = false;
    private $parent = false;
    private $headings = array();
    private $children = array();

    public function __construct($id_catalog = 0) {
        
        if (!$id_catalog)
            return;

        $result = mysql_query("SELECT * FROM `catalog` WHERE `id_catalog`='$id_catalog'");
        if (!$result)
            return;

        $row = mysql_fetch_array($result);
        $this->id_catalog = $id_catalog;
        $this->name = $row['name'];
        $this->description = $row['description'];
        $this->cover = $row['cover'];
        $this->parent = $row['parent'];
        
        include_once 'CHeading.php';
        $result = mysql_query("SELECT * FROM `catalog_heading` WHERE `id_catalog`='$id_catalog'");
        while ($row = mysql_fetch_array($result)) {
            $this->headings[] = new CHeading($row['id_heading']);
        }
        $this->get_children($this->id_catalog, $this->children);
        
    }

    public function id(){
        return $this->id_catalog;
    }


    /**
     * Возвращает массив рубрик, которые связаны с данным катологом
     */
    public function headings() {
        return $this->headings;
    }

    /**
     * Принимает массив рубрик, с которыми надо связать каталог
     */
    public function setHeadings($headings) {
        // чистим все связи каталога
        if (!mysql_query("DELETE FROM `catalog_heading` WHERE `id_catalog`='$this->id_catalog'"))
            return false;
        
        $this->headings = array();

        foreach ($headings as $heading) {
            mysql_query("INSERT INTO `catalog_heading`(`id_catalog`, `id_heading`) VALUES('$this->id_catalog','".$heading->id()." ')");
            $this->headings[] = $heading;
        }
        return true;
    }
    
    
    /**
     * Связан ли каталог с рубрикой $testHeading
     */
    public function hasHeading($testHeading){
        foreach ($this->headings as $heading){
            if($heading->id() == $testHeading->id())
                return true;
        }
        return false;
    }


        /**
     * Метод заполняет объект данными и вставляет запись в БД
     */
    public function create($name, $description, $cover, $parent) {
        if (!mysql_query("INSERT INTO `catalog`(`name`, `description`, `cover`, `parent`) VALUES('$name', '$description','$cover','$parent')"))
            return false;
        $result = mysql_query("SELECT MAX(`id_catalog`) FROM `catalog`");
        if (!$result)
            return false;
        $row = mysql_fetch_array($result);

        $this->id_catalog = $row[0];
        $this->name = $name;
        $this->description = $description;


        $this->set_cover($cover);


        $this->parent = $parent;

        wlog(1, "Добавил каталог '" . $name . "'");
        return true;
    }

    public function set_name($name) {
        if (!$this->id_catalog)
            return false;

        if ($this->name == $name)
            return true;

        if (!mysql_query("UPDATE `catalog` SET `name`='$name' WHERE `id_catalog`='$this->id_catalog'"))
            return false;

        wlog(1, "Изменил название каталога " . $this->id_catalog . " с '" . $this->name . "' на '" . $name . "'");
        $this->name = $name;
        return true;
    }

    public function set_description($description) {
        if (!$this->id_catalog)
            return false;

        if ($this->description == $description)
            return true;

        if (!mysql_query("UPDATE `catalog` SET `description`='$description' WHERE `id_catalog`='$this->id_catalog'"))
            return false;

        wlog(1, "Изменил описание каталога " . $this->name . " с '" . $this->description . "' на '" . $description . "'");
        $this->description = $description;
        return true;
    }

    public function set_cover($cover_file) {
        if (!$this->id_catalog)
            return false;

        $cover = $this->cover;
//-----------------------Загрузка файлов на сервер---------------------------
        $size = filesize($cover_file['tmp_name']);
        if ($size && $size < 8000000) {

// Удаляем старую обложку
            $fname = "../img/catalog/" . $this->cover;
            if (file_exists($fname) && is_file($fname))
                unlink($fname);

            $cover = md5(time(true)) . strstr(basename($cover_file['name']), '.');
            $path = "../img/catalog/" . $cover; //Каталог, в который будем загружать файл
// копируем из временного каталога сервера в постоянный(указанный)
            move_uploaded_file($cover_file['tmp_name'], $path) or die("Ошибка перемещения файла");

            $image = imagecreatefromjpeg($path);

            thumbnail($image, $path, 266);
        }

        if (!mysql_query("UPDATE `catalog` SET `cover`='$cover' WHERE `id_catalog`='$this->id_catalog'"))
            return false;

        $this->cover = $cover;

        wlog(1, "Изменил обложку каталога " . $this->name);
        return true;
    }

    public function set_parent($parent) {
        if (!$this->id_catalog)
            return false;

        if ($this->parent == $parent)
            return true;

        if (!mysql_query("UPDATE `catalog` SET `parent`='$parent' WHERE `id_catalog`='$this->id_catalog'"))
            return false;

        wlog(1, "Изменил родителя каталога " . $this->name . " с '" . $this->parent . "' на '" . $parent . "'");
        $this->parent = $parent;
        return true;
    }

    public function id_catalog() {
        return $this->id_catalog;
    }

    public function name() {
        return $this->name;
    }

    public function description() {
        return $this->description;
    }

    public function cover() {
        return $this->cover;
    }

    public function parent() {
        return $this->parent;
    }

    // рекурсивная функция получения списка всех идентификаторов потомков каталога
    private function get_children($id, &$children) {
        $result = mysql_query("SELECT * FROM `catalog` WHERE `parent`='$id'");
        while ($row = mysql_fetch_array($result)) {
            $id_catalog = $row['id_catalog'];
            settype($id_catalog, "int");
            array_push($children, $id_catalog);
            $this->get_children($id_catalog, $children);
        }
    }

    // возвращает список всех идентификаторов потомков каталога
    public function children() {
        return $this->children;       
    }

    // рекурсивная функция получения списка всех идентификаторов родителей каталога
    private function get_parents_id($id, &$parent) {
        $result = mysql_query("SELECT * FROM `catalog` WHERE `id_catalog`=( SELECT `parent` FROM `catalog` WHERE `id_catalog`='$id') ");
        while ($row = mysql_fetch_array($result)) {
            $id_catalog = $row['id_catalog'];
            array_push($parent, $id_catalog);
            $this->get_parents_id($id_catalog, $parent);
        }
    }

    //возвращает список всех идентификаторов родителей каталога
    public function parents_id() {
        $parents = array();
        $this->get_parents_id($this->id_catalog, $parents);
        return $parents;
    }

    // рекурсивная функция получения списка всех ссылок на имена родителей каталога
    private function get_parents_name($id, &$parent) {
        $result = mysql_query("SELECT * FROM `catalog` WHERE `id_catalog`=( SELECT `parent` FROM `catalog` WHERE `id_catalog`='$id') ");
        while ($row = mysql_fetch_array($result)) {
            $id_catalog = $row['id_catalog'];
            $name = "<a href='index.php?p=catalog&catalog=$id_catalog'>" . $row['name'] . "</a>";
            $this->get_parents_name($id_catalog, $parent);
            array_push($parent, $name);
        }
    }

    //возвращает список всех имен родителей каталога
    public function parents_name() {
        $parents = array();
        $this->get_parents_name($this->id_catalog, $parents);
        return $parents;
    }

    public function delete() {
        if (!$this->id_catalog)
            return;

        // получаем потомков каталога
        $children = $this->children();
        array_push($children, $this->id_catalog);
        $id_catalogs = implode(",", $children);


        $result = mysql_query("SELECT * FROM `catalog` WHERE `id_catalog` IN ($id_catalogs)") or die(mysql_error());
        while ($row = mysql_fetch_array($result)) {
            $cover = "../img/catalog/" . $row['cover'];
            if (file_exists($cover) && is_file($cover))
                unlink($cover);
        }
        mysql_query("DELETE FROM `product_catalog`  WHERE `id_catalog` IN ($id_catalogs) ") or die(mysql_error());
        mysql_query("DELETE FROM `catalog`          WHERE `id_catalog` IN ($id_catalogs) ") or die(mysql_error());

        wlog(1, "Удалил каталог '" . $this->name . "'");
    }

}

?>
