<?php

class CHeading {

    private $id_heading;
    private $name;
    private $onMain;

    public function __construct($id_heading) {
        if (!$id_heading)
            return;

        $result = mysql_query("SELECT * FROM `heading` WHERE `id_heading`='$id_heading'");
        if (!$result)
            return;

        $row = mysql_fetch_array($result);
        $this->id_heading = $id_heading;
        $this->name = $row['name'];
        $this->onMain = $row['onMain'];
    }
    
    public function id(){
        return $this->id_heading;
    }


    public function onMain(){
        return $this->onMain;
    }
    public function setOnMain($onMain){
        
        if($this->onMain == $onMain)
            return true;
        
        wlog(1, "Рубрика " . $this->name . " . На главной =  " . $onMain);
        
        $this->onMain = $onMain;
        
        mysql_query("UPDATE `heading` SET `onMain`='$this->onMain' WHERE `id_heading`='$this->id_heading'");
    }


    public function name(){
        return $this->name;
    }
    public function setName($name){
        if($this->name == $name)
            return;
        
        wlog(1, "Изменено название рубрики с " . $this->name . " на " . $name);
        $this->name = $name;
        
        mysql_query("UPDATE `heading` SET `name`='$this->name' WHERE `id_heading`='$this->id_heading'");
    }

    /**
     * Создание новой рубрики.
     * В случае успеха вернет объект рубрики, иначе false;
     */
    public static function create($name) {

        if (!mysql_query("INSERT INTO `heading`(`name`) VALUES('$name')"))
            return false;

        $result = mysql_query("SELECT MAX(`id_heading`) FROM `heading`");
        $row = mysql_fetch_array($result);

        wlog(1, "Создана рубрика " . $name);
        return new CHeading($row[0]);
    }

    public function delete() {
        if (!$this->id_heading)
            return false;

        if (!mysql_query("DELETE FROM `heading` WHERE `id_heading`='$this->id_heading'"))
            return false;

        wlog(1, "Удалена рубрика " . $this->name);
        return true;
    }

    public function show() {
        ?>
        <div class="list_item">
            <a href='index.php?p=heading_edit&id_heading=<?php echo $this->id_heading; ?>'> <?php echo $this->name; ?></a> 
            <a class='small_grey right' href="heading_delete.php?id_heading=<?php echo $this->id_heading; ?>" onclick="if(!confirm('Вы действительно хотите удалить рубрику ( <?php echo $this->name; ?> )?')) return false;">Удалить</a>
            <div class="clearer"></div>
        </div>
        <?php
    }

}
?>
