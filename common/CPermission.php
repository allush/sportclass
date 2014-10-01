<?php

class CPermission {

    private $id_permission = false;
    private $name = false;

    public function __construct($id_permission = 0) {
        if (!$id_permission)
            return;

        $result = mysql_query("SELECT * FROM `permission` WHERE `id_permission`='$id_permission'");
        if (!$result)
            return;

        $row = mysql_fetch_array($result);
        $this->id_permission = $id_permission;
        $this->name = $row['name'];
    }

    public function create($name) {
        //если объект инициализировани, то нельзя его заново создавать
        if ($this->id_permission)
            return false;

        if (!mysql_query("INSERT INTO `permission`(`name`) VALUES('$name')"))
            return false;

        $result = mysql_query("SELECT MAX(`id_permission`) FROM `permission`");
        if (!$result)
            return false;
        $row = mysql_fetch_array($result);

        $this->id_permission = $row[0];
        $this->name = $name;

        wlog(1, "Добавлено право '" . $name . "'");
        return true;
    }

    public function id_permission() {
        return $this->id_permission;
    }

    public function name() {
        return $this->name;
    }

    public function set_name($name) {
        if (!$this->id_permission)
            return false;

        if ($this->name == $name)
            return true;

        if (!mysql_query("UPDATE `permission` SET `name`='$name' WHERE `id_permission`='$this->id_permission'"))
            return false;

        wlog(1, "Редактировано название права с '" . $this->name . "' на '" . $name . "'");
        $this->name = $name;
        return true;
    }

    public function delete() {
        //если объект не инициализирован, то нельзя его удалять
        if (!$this->id_permission)
            return false;

        if (!mysql_query("DELETE FROM `role_permission` WHERE `id_permission`='$this->id_permission'"))
            return false;
        
        if (!mysql_query("DELETE FROM `permission` WHERE `id_permission`='$this->id_permission'"))
            return false;
        

        wlog(1, "Удалено право '" . $this->name . "'");
        return true;
    }

}

?>
