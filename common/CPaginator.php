<?php

class CPaginator {

    /**
     * Функция принимает 3 параметра
     * 1 - сколько всего элементов в выборке
     * 2 - с какого элемента выводить
     * 3 - сколько выводить
     */
    public static function show($all, $from, $part) {
        if ($all <= $part)
            return;

        $mod = $all % $part;
        $num_pages = (int) ( $all / $part);
        if ($mod)
            ++$num_pages;


        $queryString = $_SERVER['QUERY_STRING'];
        //ecли установлено from, то нужно исключить из строки запроса
        if (isset($_GET['from'])) {
            $queryArr = explode("&", $queryString);
            array_pop($queryArr);
            $queryString = implode("&", $queryArr);
        }
        ?>

        <div class="paginator">
            <?php
            $back = $from - $part;
            if ($back >= 0) {
                $backLink = $_SERVER['SCRIPT_NAME'] . "?" . $queryString . "&from=" . ($from - $part);
                ?>
                <a class="backLink" href="<?php echo $backLink; ?>">&lArr; Назад</a>
                <?php
            }
            ?>

            <form name="page" action="index.php" method="get" >
                <input type="hidden" name="p" value="<?php echo $_GET['p']; ?>" />
                <?php
                if (isset($_GET['catalog'])) {
                    ?>
                <input type="hidden" name="catalog" value="<?php echo $_GET['catalog']; ?>" />
                    <?php
                }
                ?>

                <select name="from">
                    <?php for ($i = 1; $i <= $num_pages; $i++) { ?>
                        <option <?php if (($from / $part) == ($i - 1)) echo "selected='yes'" ?> value='<?php echo (($i - 1) * $part); ?>'><?php echo $i; ?></option>
                        <?php
                    }
                    ?>
                </select>
                <button>Перейти</button>
            </form>

            <?php
            $next = $from + $part;
            if ($next < $all) {
                $nextLink = $_SERVER['SCRIPT_NAME'] . "?" . $queryString . "&from=" . ($from + $part);
                ?>
                <a class="nextLink" href="<?php echo $nextLink; ?>">Вперед &rArr;</a>
                <?php
            }
            ?>
        </div>

        <?php
    }

}
?>
