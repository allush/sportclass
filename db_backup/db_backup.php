<?php
// Функция резервного копирования базы данных
function backup_database_tables($tables) {    
    //Получаем все таблицы
    if ($tables == '*') {
        $tables = array();
        $result = mysql_query('SHOW TABLES');
        while ($row = mysql_fetch_row($result)) {
            $tables[] = $row[0];
        }
    } 
    else{
        $tables = is_array($tables) ? $tables : explode(',', $tables);
    }


    //Цикл по всем таблицам и формирование данных
    $return = "";
    foreach ($tables as $table) {
        $result = mysql_query('SELECT * FROM ' . $table);
        $num_fields = mysql_num_fields($result);
        
//        $return.= 'DROP TABLE ' . $table . ';';
        $row2 = mysql_fetch_row(mysql_query('SHOW CREATE TABLE ' . $table));
        $return.= "\n\n" . $row2[1] . ";\n\n";
        for ($i = 0; $i < $num_fields; $i++) {
            while ($row = mysql_fetch_row($result)) {
                $return.= 'INSERT INTO ' . $table . ' VALUES(';
                for ($j = 0; $j < $num_fields; $j++) {
                    $row[$j] = addslashes($row[$j]);
                    $row[$j] = ereg_replace("\n", "\\n", $row[$j]);
                    if (isset($row[$j])) {
                        $return.= '"' . $row[$j] . '"';
                    } else {
                        $return.= '""';
                    }

                    if ($j < ($num_fields - 1)) {
                        $return.= ',';
                    }
                }
                $return.= ");\n";
            }
        }
        $return.="\n\n\n";
    }



    //Сохраняем файл

    $handle = fopen('dump/db-backup-' . time() . '-' . (md5(implode(',', $tables))) . '.sql', 'w+');

    if($handle)
    {
        fwrite($handle, $return);

        fclose($handle);
    }
}
include '../common/connect.php';
backup_database_tables('*');
?>
