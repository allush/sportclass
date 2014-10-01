<?php

include 'CRole.php';

class CUser {

    protected $id_user = false;
    protected $first_name = false;
    protected $last_name = false;
    protected $patronymic = false;
    protected $photo = false;
    protected $info = false;
    protected $country = false;
    protected $sity = false;
    protected $zip_code = false;
    protected $street = false;
    protected $house = false;
    protected $composition = false;
    protected $building = false;
    protected $flat = false;
    protected $office = false;
    protected $phone = false;
    protected $email = false;
    protected $password = false;
    protected $counter = false;
    protected $activated = false;
    protected $date_last_activity = false;
    protected $roles = array();
    protected $deleted = false;
    protected $cash = false;
    protected $cash_last_date = false;
    protected $cash_last_money = false;

    public function __construct($id_user) {
        if (!$id_user)
            return;

        $result = mysql_query("SELECT * FROM `user` WHERE `id_user`='$id_user'");
        if (!$result)
            return;
        $row = mysql_fetch_array($result);
        if (!$row)
            return;

        $this->id_user = $id_user;
        $this->first_name = $row['first_name'];
        $this->last_name = $row['last_name'];
        $this->patronymic = $row['patronymic'];
        $this->photo = $row['photo'];
        $this->info = $row['info'];
        $this->country = $row['country'];
        $this->sity = $row['sity'];
        $this->zip_code = $row['zip_code'];
        $this->street = $row['street'];
        $this->house = $row['house'];
        $this->composition = $row['composition'];
        $this->building = $row['building'];
        $this->flat = $row['flat'];
        $this->office = $row['office'];
        $this->phone = $row['phone'];
        $this->email = $row['email'];
        $this->password = $row['password'];
        $this->counter = $row['counter'];
        $this->activated = $row['activated'];
        $this->date_last_activity = $row['date_last_activity'];
        $this->deleted = $row['deleted'];
        $this->cash = $row['cash'];
        $this->cash_last_date = $row['cash_last_date'];
        $this->cash_last_money = $row['cash_last_money'];

        $result = mysql_query("SELECT * FROM `user_role` WHERE `id_user`='$this->id_user'") or die("!user");
        while ($row = mysql_fetch_array($result)) {
            array_push($this->roles, new CRole($row['id_role']));
        }
    }

    public function cash_last_date() {
        return $this->cash_last_date;
    }

    public function cash_last_money() {
        return $this->cash_last_money;
    }

    public function enter() {
        wlog(1, "Вошел в систему");
    }

    public function out() {
        wlog(1, "Вышел из системы");

        $params = session_get_cookie_params();
        setcookie("PHPSESSID", "", $params['lifetime'], $params['path'], $params['domain'], $params['secure']);
        $_SESSION = array();
        session_destroy();
    }

    public function photo() {
        return $this->photo;
    }

    public function set_photo($fphoto) {
        if (!$this->id_user)
            return false;
        include '../admin/thumbnail.php';

//-----------------------Загрузка файлов на сервер---------------------------
        $size = filesize($fphoto['tmp_name']);
        if ($size && $size < 8000000) {

            // Удаляем старую обложку
            $fname = "../img/master/" . $this->photo;
            if (file_exists($fname) && is_file($fname))
                unlink($fname);

            $this->photo = md5(time(true)) . strstr(basename($fphoto['name']), '.');
            $path = "../img/master/" . $this->photo;
            //Каталог, в который будем загружать файл
            //копируем из временного каталога сервера в постоянный(указанный)
            move_uploaded_file($fphoto['tmp_name'], $path) or die("Ошибка перемещения файла");

            $image = imagecreatefromjpeg($path);

            thumbnail($image, $path, 200);
        }

        if (!mysql_query("UPDATE `user` SET `photo`='$this->photo' WHERE `id_user`='$this->id_user'"))
            return false;

        wlog(1, "Изменил фото пользователя");
        return true;
    }

    public function info() {
        return $this->info;
    }

    public function set_info($info) {
        $this->info = htmlspecialchars($info, ENT_QUOTES);

        wlog(1, "Изменена информация о пользователе");

        mysql_query("UPDATE `user` SET `info`='$this->info' WHERE `id_user`='$this->id_user'");
    }

    public function id_user() {
        return $this->id_user;
    }

    public function first_name() {
        return $this->first_name;
    }

    public function set_first_name($first_name) {
        if ($this->first_name == $first_name)
            return;
        wlog(1, "Изменено имя с " . $this->first_name . " на " . $first_name);

        $this->first_name = $first_name;
        mysql_query("UPDATE `user` SET `first_name`='$this->first_name' WHERE `id_user`='$this->id_user'");
    }

    public function last_name() {
        return $this->last_name;
    }

    public function set_last_name($last_name) {
        if ($this->last_name == $last_name)
            return;
        wlog(1, "Изменена фамилия с " . $this->last_name . " на " . $last_name);

        $this->last_name = $last_name;
        mysql_query("UPDATE `user` SET `last_name`='$this->last_name' WHERE `id_user`='$this->id_user'");
    }

    public function patronymic() {
        return $this->patronymic;
    }

    public function set_patronymic($patronymic) {
        if ($this->patronymic == $patronymic)
            return;
        wlog(1, "Изменено отчество с " . $this->patronymic . " на " . $patronymic);

        $this->patronymic = $patronymic;
        mysql_query("UPDATE `user` SET `patronymic`='$this->patronymic' WHERE `id_user`='$this->id_user'");
    }

    public function country() {
        return $this->country;
    }

    public function set_country($country) {
        if ($this->country == $country)
            return;
        wlog(1, "Изменена страна с " . $this->country . " на " . $country);

        $this->country = $country;
        mysql_query("UPDATE `user` SET `country`='$this->country' WHERE `id_user`='$this->id_user'");
    }

    public function sity() {
        return $this->sity;
    }

    public function set_sity($sity) {
        if ($this->sity == $sity)
            return;
        wlog(1, "Изменен город с " . $this->sity . " на " . $sity);

        $this->sity = $sity;
        mysql_query("UPDATE `user` SET `sity`='$this->sity' WHERE `id_user`='$this->id_user'");
    }

    public function zip_code() {
        return $this->zip_code;
    }

    public function set_zip_code($zip_code) {
        if ($this->zip_code == $zip_code)
            return;
        wlog(1, "Изменен индекс с " . $this->zip_code . " на " . $zip_code);

        $this->zip_code = $zip_code;
        mysql_query("UPDATE `user` SET `zip_code`='$this->zip_code' WHERE `id_user`='$this->id_user'");
    }

    public function street() {
        return $this->street;
    }

    public function set_street($street) {
        if ($this->street == $street)
            return;
        wlog(1, "Изменена улица с " . $this->street . " на " . $street);

        $this->street = $street;
        mysql_query("UPDATE `user` SET `street`='$this->street' WHERE `id_user`='$this->id_user'");
    }

    public function house() {
        return $this->house;
    }

    public function set_house($house) {
        if ($this->house == $house)
            return;
        wlog(1, "Изменен дом с " . $this->house . " на " . $house);

        $this->house = $house;
        mysql_query("UPDATE `user` SET `house`='$this->house' WHERE `id_user`='$this->id_user'");
    }

    public function composition() {
        return $this->composition;
    }

    public function set_composition($composition) {
        if ($this->composition == $composition)
            return;
        wlog(1, "Изменен корпус с " . $this->composition . " на " . $composition);

        $this->composition = $composition;
        mysql_query("UPDATE `user` SET `composition`='$this->composition' WHERE `id_user`='$this->id_user'");
    }

    public function building() {
        return $this->building;
    }

    public function set_building($building) {
        if ($this->building == $building)
            return;
        wlog(1, "Изменено строение с " . $this->building . " на " . $building);

        $this->building = $building;
        mysql_query("UPDATE `user` SET `building`='$this->building' WHERE `id_user`='$this->id_user'");
    }

    public function flat() {
        return $this->flat;
    }

    public function set_flat($flat) {
        if ($this->flat == $flat)
            return;
        wlog(1, "Изменена квартира с " . $this->flat . " на " . $flat);

        $this->flat = $flat;
        mysql_query("UPDATE `user` SET `flat`='$this->flat' WHERE `id_user`='$this->id_user'");
    }

    public function office() {
        return $this->office;
    }

    public function set_office($office) {
        if ($this->office == $office)
            return;
        wlog(1, "Изменен офис с " . $this->office . " на " . $office);

        $this->office = $office;
        mysql_query("UPDATE `user` SET `office`='$this->office' WHERE `id_user`='$this->id_user'");
    }

    public function phone() {
        return $this->phone;
    }

    public function set_phone($phone) {
        if ($this->phone == $phone)
            return;
        wlog(1, "Изменен номер телефона с " . $this->phone . " на " . $phone);

        $this->phone = $phone;
        mysql_query("UPDATE `user` SET `phone`='$this->phone' WHERE `id_user`='$this->id_user'");
    }

    public function email() {
        return $this->email;
    }

    public function set_email($email) {
        if ($this->email == $email)
            return;
        wlog(1, "Изменен email с " . $this->email . " на " . $email);

        $this->email = $email;
        mysql_query("UPDATE `user` SET `email`='$this->email' WHERE `id_user`='$this->id_user'");
    }

    public function password() {
        return $this->password;
    }

    public function set_password($password) {
        if ($this->password == md5($password))
            return;
        wlog(1, "Изменен пароль");

        $this->password = md5($password);
        mysql_query("UPDATE `user` SET `password`='$this->password' WHERE `id_user`='$this->id_user'");
    }

    public function counter() {
        return $this->counter;
    }

    public function activated() {
        return $this->activated;
    }

    public function date_last_activity() {
        return $this->date_last_activity;
    }

    public function deleted() {
        return $this->deleted;
    }

    public function cash() {
        return $this->cash;
    }

    public function set_cash($cash) {
        if (!is_numeric($cash))
            return 0;

        if ($this->cash == $cash)
            return 0;

        $this->cash = $cash;

        mysql_query("UPDATE `user` SET `cash`='$this->cash' WHERE `id_user`='$this->id_user'");
    }

    public function take_cash() {
        if (!$this->cash)
            return;

        $this->cash_last_money = $this->cash;
        $this->cash = 0;
        $this->cash_last_date = date();

        mysql_query("UPDATE `user` SET `cash`='$this->cash', `cash_last_date`=NOW(),`cash_last_money`='$this->cash_last_money'  WHERE `id_user`='$this->id_user'");
    }

    /**
     * Возвращает список ролей
     */
    public function roles() {
        return $this->roles;
    }

    public function set_roles($roles) {
        mysql_query("DELETE FROM `user_role` WHERE `id_user`='$this->id_user'") or die(mysql_error());
        foreach ($roles as $id_role)
            mysql_query("INSERT INTO `user_role`( `id_user`, `id_role` ) VALUES( '$this->id_user', '" . $id_role . "' )") or die(mysql_error());
    }

    public function has_role($id_role) {
        foreach ($this->roles as $role) {
            if ($role->id_role() == $id_role)
                return true;
        }
        return false;
    }

    /**
     * Проверка полномочий пользователя
     */
    public function has_permission($permissions, $write_log = 1) {
        $read = 0;
        $write = 0;

        $key = 0;

        foreach ($permissions as $permission) {

            foreach ($permission as $id_permission => $perm) {

                foreach ($this->roles as $role) {
                    if ($role->has_permission_write($id_permission))
                        $write = 1;

                    if ($role->has_permission_read($id_permission))
                        $read = 1;

                    if ($perm['read']) {
                        // если требуется право на чтение, и у этой роли пользователя оно имеется право или на чтение или на запись , то перейти к проверке на запись, иначе ошибка доступа
                        if ($read || $write)
                            $key = 1;
                        else {
                            $key = 0;
                            continue;
                        }
                    }

                    if ($perm['write']) {
                        // если требуется право на запись и оно имеется у этой роли пользователя, то  перейти к следующему требованию
                        if ($write)
                            $key = 1;
                        else
                            $key = 0;
                    }

                    $read = 0;
                    $write = 0;

                    if ($key)
                        break;
                }
            }
            // если предыдущее требование не было удовлетворено ни одной из ролей пользователя, то это ошибка доступа, иначе - хорошо и смотрим на очередное требование
            if (!$key) {

                if ($write_log)
                    wlog(0, "Ошибка доступа");
                return false;
            }
        }
//        if ($write_log)
//            wlog(1, "Доступ разрешен");
        return true;
    }

    public function delete() {
//        mysql_query("DELETE FROM `user_role` WHERE `id_user`='$this->id_user'") or die(mysql_error());
//        mysql_query("DELETE FROM `user` WHERE `id_user`='$this->id_user'") or die(mysql_error());

        mysql_query("UPDATE `user` SET `deleted`='1' WHERE `id_user`='$this->id_user'");
    }

    public function undelete() {
        mysql_query("UPDATE `user` SET `deleted`='0' WHERE `id_user`='$this->id_user'");
    }

}

?>
