<?php

function p_permission() {
    return array(array("6" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Оборот :: Продажи";
}

function p_description() {
    return "Оборот :: Продажи";
}

function p_dark_block() {
    ?>
    <a href="index.php?p=income">Приход</a>
    <a href="index.php?p=allowances">Списание</a>
    <a href="index.php?p=sale">Продажи</a>
    <a href="index.php?p=profit">Прибыль</a>
    <?php
}

function p_navigation() {
    ?>
    <a href="index.php?p=<?php echo $_GET['p']; ?>&period=today">Сегодня</a>
    <a href="index.php?p=<?php echo $_GET['p']; ?>&period=week">На этой неделе</a>
    <a href="index.php?p=<?php echo $_GET['p']; ?>&period=month">В этом месяце</a>
    <a href="index.php?p=<?php echo $_GET['p']; ?>&period=year">В этом году</a>
    <form action="index.php" method="get" style="display: inline;">
        <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
        <input type="hidden" name="period" value="period" />
        <input type="date" name="from" />
        <input type="date" name="to" />
        <input type="submit" value="Ок"/>
    </form>
    <a href="generate_xls.php"><img title="Экспортировать в Excel" src="img/excel.png" /></a>
    <?php
}

function p_content() {
    //по умолчанию за сегодня
    $query = "SELECT * FROM `turnover` WHERE `id_turnover_type`='3' AND DATE(`date`)=CURDATE()";
    if (isset($_GET['period'])) {
        switch ($_GET['period']) {
            case "today":
                $query = "SELECT * FROM `turnover` WHERE `id_turnover_type`='3' AND DATE(`date`)=CURDATE()";
                break;
            case "week":
                $query = "SELECT * FROM `turnover` WHERE `id_turnover_type`='3' AND DATEDIFF(NOW(),DATE(`date`)) <= WEEKDAY(NOW())";
                break;
            case "month":
                $query = "SELECT * FROM `turnover` WHERE `id_turnover_type`='3' AND DATEDIFF(NOW(),DATE(`date`)) <= DAY(NOW())";
                break;
            case "year":
                $query = "SELECT * FROM `turnover` WHERE `id_turnover_type`='3' AND YEAR(`date`)=YEAR(NOW())";
                break;
            case "period":
                $from = $_GET['from'];
                $to = $_GET['to'];
                $query = "SELECT * FROM `turnover` WHERE `id_turnover_type`='3' AND DATE(`date`) <= DATE('$to') AND DATE(`date`) >= DATE('$from') ";
                break;
        }
    }
    $query .= " ORDER BY `date` DESC";
    $result = mysql_query($query) or die(mysql_error());
    if (!mysql_num_rows($result)) {
        echo "Нет продаж за выбранный период";
        return;
    }
    $s = 0;
    $percent = array();
    while ($row = mysql_fetch_array($result)) {
        $turnover = new CSale($row['id_turnover']);
        $turnover->show();
        $s += $turnover->sum();            
        $percent[$turnover->id_user()] += $turnover->seller_percent();
    }
    echo "Общая сумма = " . $s."<br>";

    foreach ($percent as $key => $value){
        $user = new CUser($key);
        echo $user->last_name()." ".$user->first_name()." = ".$value."<br>";
    }
    include 'set_last_query.php';
    echo set_last_query($query);
}
?>