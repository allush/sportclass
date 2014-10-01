<?php

function p_permission() {
    return array(array("3" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Фильтр товаров";
}

function p_description() {
    return "Фильтр товаров";
}

function p_dark_block() {
    ?>
    <a href='index.php?p=product'>Все товары</a>
    <a href="index.php?p=product_filter">Поиск</a>
    <?php
    $user = new CUser($_SESSION['id_user']);
    if ($user->has_permission(array(array("3" => array("write" => "1", "read" => "1"))), 0)) {
        ?>
        <a href='index.php?p=product_add'>Добавить</a>
        <?php
    }
    ?>
    <a href="index.php?p=price_tag">Ценники</a>
    <?php
}

function p_content() {
    ?>

    <form name="filter" action="product_filter.php" method="post">
        <p><input id="search"          type="text"     name="keyword"   placeholder="Поиск: введите название или идентификатор товара"  /></p>
        <p><input id="without_catalog" type="checkbox" name="without_catalog" />   <label for="without_catalog">Новые</label></p>
        <p><input id="hidden"          type="checkbox" name="hidden" />            <label for="hidden">Скрытый</label></p>
        <p><input id="existence"       type="checkbox" name="existence" />         <label for="existence">Отсутствует</label></p>
        <p><input id="discount"        type="checkbox" name="discount" />          <label for="discount">Со скидкой</label></p>
        <p><input id="deleted"         type="checkbox" name="deleted" />           <label for="deleted">Удаленные</label></p>

        <?php

        function catalogs($id = 0, $margin = 0) {
            $res = mysql_query("SELECT * FROM `catalog` WHERE `parent`='$id'");
            $num = mysql_num_rows($res);
            if ($num > 0) {
                for ($i = 0; $i < $num; $i++) {
                    $row = mysql_fetch_array($res);
                    $id_catalog = $row['id_catalog'];
                    $name = $row['name'];
                    ?>
                    <div style='margin-left: <?php echo $margin . "px"; ?>'>
                        <input onclick="$('form[name=filter]').submit();" id="catalog<?php echo $id_catalog; ?>"type="radio" name="id_catalog" value="<?php echo $id_catalog; ?>"/> <label for="catalog<?php echo $id_catalog; ?>"><?php echo $name; ?></label>
                    </div>

                    <?php
                    $margin +=16;
                    catalogs($id_catalog, $margin);
                    $margin -=16;
                }
            }
        }
        ?>
        <div id="catalogs">
            <div>
                <input onclick="$('form[name=filter]').submit();" id="catalog0" type="radio" name="id_catalog" checked value="0"/> <label for="catalog0">Все</label>
            </div>
            <?php catalogs(0, 16); ?>
            <div class="clearer"></div>
        </div>    
    </form>

    <div id="popup"></div>

    <div class="manageBlock">
        <button id='show_hide'>Развернуть все</button>
        <button id='select_deselect'>Выделить все</button>

        <p class="groupAction">
            С выделенными:
            <a onclick="setProductOptions();"> <img width="16" title="Перенести в каталог" alt="" src="img/catalog.png">  </a>
            <a onclick="priceTagSelectedProduct();"> <img width="16" title="Создать ценники" alt="" src="img/price_tag.png">  </a>
            <a onclick="joinProducts();"> <img width="16" title="Объединить" alt="" src="img/join.png">  </a>
            <a onclick="saveSelectedProduct();"> <img width="16" title="Сохранить" alt="" src="img/save.png"> </a>
            <a onclick="undeleteSelectedProduct();"> <img width="16" title="Восстановить" alt="" src="img/undelete.png"> </a>
            <a onclick="if(!confirm('Вы действительно хотите удалить выделенные товары?')) return false; deleteSelectedProduct();"> <img width="16" title="Удалить" alt="" src="img/delete.png"> </a>
        </p>
    </div>

    <div id="search_result"></div>


    <script type="text/javascript">
        //связать щелчки по опциям поиска с отправкой формы
        $("form[name=filter] input[type=checkbox]").click( function(){
            var arr = $("form[name=filter] input[type=checkbox]").toArray();
            for(var i = 0; i < arr.length; i++){
                if(arr[i].checked){
                    $("form[name=filter]").submit();
                    break;
                }
            }
        })


        var is_visible = false; // видимость выводимых товаров. 0 - свернуты
                                    
        $(".article_img img").dblclick(function(){
            //получаем список классов картинки
            var img_class = $(this).parent().attr('class');
            //получаем идентификатор(он является 2м классом)
            var img_id = img_class.split(" ");
            var id = parseInt(img_id[1], 10);
                                                                                                                                                                                                                                
            product_toggle(id)
        });                                        
                                                
                                                
        $("#show_hide").click( function(){ 
            is_visible = !is_visible;             
            refresh();
        });
                                                  
        var is_selected = 0;
        $("#select_deselect").click( function(){ 
            is_selected = !is_selected;             
            refresh_selection();
        });
                                                       
        refresh();                 
                                    
                                    
                                    
        $("form[name=filter]").submit(function(event){


            var elems = document.forms['filter'].elements;

            $.ajax({
                type: "post",
                url: "product_filter.php",
                data: {
                    ajax:1,
                    keyword: elems['keyword'].value,
                    hidden: elems['hidden'].checked,
                    without_catalog: elems['without_catalog'].checked,
                    existence: elems['existence'].checked,
                    discount: elems['discount'].checked,
                    deleted: elems['deleted'].checked,
                    id_catalog: elems['id_catalog'].value
                },
                success: function(data, code)
                {
                    if("success" == code){
                        $("#search_result").show();

                        if(data == 0)
                            $("#search_result").html("<p id='close'>Скрыть результаты поиска</p> По запросу<i>\""+$("input[name=keyword]").attr("value")+"\"</i> ничего не найдено!");
                        else if(data == -1)
                            $("#search_result").html("<p id='close'>Скрыть результаты поиска</p> Ошибка доступа!");
                        else {

                            $("#search_result").html("<p id='close'>Скрыть результаты поиска</p>"+data);
                            //связываем щелчок по товару с его выделением
                            $(".article_item .article_action input").click(function(){ 
                                product_toggle_selection($(this).attr("value"));
                            });  
                        }

                        $("#search_result #close").click(function(){
                            $(this).parent().hide();
                            $("input[name=keyword]").attr("value","");
                            $("form[name=filter] input[type=checkbox]").attr("checked","");
                            is_visible = 0;
                            is_selected = 0;
                            refresh();
                            refresh_selection();
                        });
                        $(".article_img").unbind("dblclick");
                        $(".article_img").dblclick(function(){
                            //получаем список классов картинки
                            var img_class = $(this).attr('class');
                            //получаем идентификатор(он является 2м классом)
                            var img_id = img_class.split(" ");
                            var id = parseInt(img_id[1], 10);

                            product_toggle(id)
                        });
                        refresh();

                    }
                },
                beforeSend: function(){
                    $("#search_result").show();
                    $("#search_result").html("<img src='img/ajax-loader.gif'/>");
                }
            });
            return false;
        });
    </script>

    <?php
}
?>