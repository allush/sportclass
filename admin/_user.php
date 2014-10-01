<?php

function p_permission() {
    return array(array("1" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Безопасность :: Логи";
}

function p_description() {
    return "Безопасность :: Логи";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=user">Пользователи</a>
    <a href="index.php?p=role">Роли</a>
    <a href="index.php?p=permission">Объекты доступа</a>
    <a href="index.php?p=log">Логи</a>
<?php
}

function p_navigation() {
    ?>
    <a href="index.php?p=<?php echo $_GET['p']; ?>&period=today<?php if(isset($_GET['id_user'])) echo "&id_user=".$_GET['id_user']; ?>" >Сегодня</a>
    <form name="date" action="index.php" method="get" style="display: inline;">
        <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
        <input type="hidden" name="period" value="period" />
        <input type="date" name="date" <?php if(isset($_GET['date'])) echo "value='".$_GET['date']."'";?> oninput="$('form[name=date]').submit();" />
        <?php if(isset($_GET['id_user'])){?>
            <input type="hidden" name="id_user" value="<?php echo $_GET['id_user'];?>" />
        <?php }?>
        <noscript><input type="submit" value="Ок"/></noscript>
    </form>

    <form name="id_user" action="index.php" method="get" style="display: inline;">
        <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
        <?php if(isset($_GET['period'])){?>
            <input type="hidden" name="period" value="<?php echo $_GET['period'];?>" />
        <?php }?>
        <?php if(isset($_GET['date'])){?>
            <input type="hidden" name="date" value="<?php echo $_GET['date'];?>" />
        <?php }?>
        <select name="id_user" onchange="$('form[name=id_user]').submit();">
            <?php
            $query = "SELECT `id_user` FROM `log` WHERE DATE(`datetime`) = DATE(NOW()) GROUP BY `id_user`";
            if (isset($_GET['period'])) {
                switch ($_GET['period']) {
                    case "today":
                        $query = "SELECT `id_user` FROM `log` WHERE DATE(`datetime`) = DATE(NOW()) GROUP BY `id_user`";
                        break;
                    case "period":
                        $date = $_GET['date'];
                        $query = "SELECT `id_user` FROM `log` WHERE  DATE(`datetime`) = '$date' GROUP BY `id_user`";
                        break;
                }
            }
            $res_users = mysql_query($query);
            if(!$res_users)
                return;
            while($row = mysql_fetch_array($res_users)){
                $user = new CUser($row['id_user']);
                echo "<option value='".$user->id_user()."'";
                if(isset($_GET['id_user']) && $row['id_user'] == $_GET['id_user'])
                    echo " selected='yes' ";
                echo ">".$user->last_name()." ".$user->first_name()."</option>";
            }
            ?>
        </select>
        <noscript><input type="submit" value="Ок"/></noscript>
    </form>
<?php
}

function p_content() {
    //по умолчанию за сегодня
    $id_user = "";
    if(isset($_GET['id_user']))
        $id_user = " AND `id_user`='".$_GET['id_user']."'";

    $query = "SELECT * FROM `log` WHERE DATE(`datetime`) = DATE(NOW()) $id_user";
    if (isset($_GET['period'])) {
        switch ($_GET['period']) {
            case "today":
                $query = "SELECT * FROM `log` WHERE DATE(`datetime`) = DATE(NOW()) $id_user";
                break;
            case "period":
                $date = $_GET['date'];
                $query = "SELECT * FROM `log` WHERE DATE(`datetime`) = '$date' $id_user";
                break;
        }
    }
    $result = mysql_query($query . " ORDER BY `id_log` DESC");
    if (!$result)
        return;
    echo "<table class='log'>";
    while ($row = mysql_fetch_array($result)) {
        $user = new CUser($row['id_user']);

        echo "<tr>
            <td>" . $row['status'] . "</td>
            <td>" . $user->last_name() . " " . $user->first_name() . "</td>
            <td>" . $row['datetime'] . "</td>
            <td>" . $row['description'] . "</td>
            <td>" . $row['uri'] . "</td>
            </tr>";
    }
    echo "</table>";
}
?>