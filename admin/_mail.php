<?php

function p_permission() {
    return array(array("11" => array("write" => "0", "read" => "1")));
}

function p_title() {
    return "Рассылка писем";
}

function p_description() {
    return "Рассылка писем";
}

function p_dark_block() {
    
}

function p_content() {
    ?>
    <form action="mail.php" method="post">
        <div style="float: right;">
            <p><b>Роль:</b></p>
            <?php
            $result = mysql_query("SELECT * FROM `role`");
            while ($row = mysql_fetch_array($result)) {
                ?>
                <p><input class="role" id="role<?php echo $row['id_role']; ?>" name="role[]" type="checkbox" value="<?php echo $row['id_role']; ?>"/> <label for="role<?php echo $row['id_role']; ?>"><?php echo $row['name']; ?></label></p>
                <?php
            }
            ?>
            <p><b>Пользователи:</b></p>
            <select id="users" name="users[]" size="22" multiple="multiple" style="width: 300px;">  </select>
        </div>

        <p><b>Тема:</b></p>
        <p><input style="width: 450px;" type="text" name="subject" required="required" /></p>

        <p><b>Содержание:</b></p>
        <p><textarea style="width: 452px; max-width: 452px; height: 400px;" name="body" required="required"></textarea></p>
        <p><button>Отправить письма</button></p>
        <p class="clearer"></p>
    </form>


    <script type="text/javascript">
        $(".role").click(function(){
            var selectedRoles = [];
            var roles = $(".role");
            for(var i = 0, j = 0; i < roles.length; i++){
                if(roles[i].checked)
                    selectedRoles[j++] = roles[i].value;
            }
                                
            $.ajax({
                type: "post",
                url: "get_user_by_role.php",
                data: {
                    roles: selectedRoles
                },
                success: function(data, code)
                {            
                    if('success' != code )
                        return;
                    $("select#users").html(data);
                },
                beforeSend: function(){      
                    $("select#users").html("<option>Загрузка...</option>");     
                }
            });
        });
    </script>
    <?php
}
?>
