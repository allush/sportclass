<?php

include 'CPermission.php';

class CRole {

    private $id_role = false;
    private $name = false;
    private $permissions = array();

    public function __construct($id_role = 0) {
        if (!$id_role)
            return;

        $result = mysql_query("SELECT * FROM `role` WHERE `id_role`='$id_role'");
        if (!$result)
            return;

        $row = mysql_fetch_array($result);
        $this->id_role = $id_role;
        $this->name = $row['name'];


        $result = mysql_query("SELECT * FROM `role_permission` WHERE `id_role`='$this->id_role'") or die(mysql_error());
        while ($row = mysql_fetch_array($result)) {
            array_push($this->permissions, new CPermission($row['id_permission']));
        }
    }

    public function create($name) {
        if (!mysql_query("INSERT INTO `role`(`name`) VALUES('$name')"))
            return false;
        $this->name = $name;
        
        $result = mysql_query("SELECT MAX(`id_role`) FROM `role`");
        if (!$result)
            return false;

        $row = mysql_fetch_array($result);

        $this->id_role = $row[0];
        
        wlog(1, "Создана роль ".$this->name);
        return true;
    }

    public function add_permission($id_permission, $read, $write) {
        if (!mysql_query("INSERT INTO `role_permission`(`id_role`, `id_permission`, `read`, `write`) VALUES($this->id_role, '$id_permission', '$read', '$write')"))
            return false;
        array_push($this->permissions, new CPermission($id_permission));
        wlog(1, "Добавлено право ".$id_permission." для роли ".$this->name);
        return true;
    }

    public function id_role() {
        return $this->id_role;
    }

    public function name() {
        return $this->name;
    }
    
   

    public function set_name($name) {
        if(!$this->id_role)
            return false;
        
        if($this->name == $name)
            return true;
        
        if(!mysql_query("UPDATE `role` SET `name`='$name' WHERE `id_role`='$this->id_role'"))
            return false;
        
        wlog(1, "Изменил имя роли ".$this->name." на ".$name);
        $this->name = $name;
    }

    public function has_permission_read($id_permission) {
        $result = mysql_query("SELECT * FROM `role_permission` WHERE `id_role`='$this->id_role' AND `id_permission`='$id_permission'") or die(mysql_error());
        $row = mysql_fetch_array($result);
        if (!$row)
            return false;

        settype($row['read'], "bool");

        return $row['read'];
    }

    public function has_permission_write($id_permission) {
        $result = mysql_query("SELECT * FROM `role_permission` WHERE `id_role`='$this->id_role' AND `id_permission`='$id_permission'") or die(mysql_error());
        $row = mysql_fetch_array($result);
        if (!$row)
            return false;

        settype($row['write'], "bool");

        return $row['write'];
    }

    public function has_permission($id_permission) {
        foreach ($this->permissions as $permission) {
            if ($permission->id_permission() == $id_permission)
                return true;
        }
        return false;
    }

    /**
     * Удаление роли.
     * При удалении роли удаляются также пользователи данной роли
     */
    public function delete() {  
        mysql_query("DELETE FROM `user_role` WHERE `id_role`='$this->id_role'") or die(mysql_error());         
        mysql_query("DELETE FROM `user` WHERE `id_user` IN ( SELECT `id_user` FROM `user_role` WHERE `id_role`='$this->id_role')") or die(mysql_error());
        mysql_query("DELETE FROM `role_permission` WHERE `id_role`='$this->id_role'") or die(mysql_error());
        mysql_query("DELETE FROM `role` WHERE `id_role`='$this->id_role'") or die(mysql_error());
        wlog(1, "Удалил роль ".$this->name);
    }

}

?>
