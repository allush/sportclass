function generate_price_tag(){
    var type = document.forms['generate_price_tag'].elements['type'].value;    
    $.ajax({
        type: "post",
        url: "generate_price_tag.php",
        data: {
            type: type
        },
        success: function(data, code)
        {            
            if('success' != code )
                return;
            $(".price_tag_item").remove();
            $("#result_generate_price_tag").html(data);
        },
        beforeSend: function(){      
            $("#result_generate_price_tag").html("<img src='img/ajax-loader.gif'/>");
        }
    });
}
function clear_price_tag(){    
    var price_tag_items = $(".price_tag_item a").toArray();
    for(var i = 0; i < price_tag_items.length; i++){
        var id_product = price_tag_items[i].className.split(" ")[2];             
        price_tag_delete(id_product);
    }
}

function price_tag_delete(id_product){
    $.ajax({
        type: "get",
        url: "price_tag_delete.php",
        data: {
            id_product: id_product,
            ajax: 1
        },
        success: function(data, code)
        {            
            if('success' != code )
                return false;
            data = parseInt(data, 10);
            
            if(-1 == data)
                alert("Ошибка доступа!");
            else if(1 == data)
                $("#price_tag"+id_product).remove();
        }
    });
}

//Добавить в список ценников
function price_tag_add(id_product){
    $.ajax({
        type: "get",
        url: "price_tag_add.php",
        data: {
            id_product: id_product,            
            ajax: 1
        },
        success: function(data, code)
        {            
            if('success' != code )
                return;
            $('#load'+id_product).css("padding", "4px");
            $('#load'+id_product).css("background-color", "#fcaf6f");
            $('#load'+id_product).html(data);
        },
        beforeSend: function(){      
            $('#load'+id_product).html("");     
        }
    });
}

function setProductOptions(){
    var ids=[];
    var id = 0;
    var items = $(".article_item");
    
    for(var i = 0; i < items.length; i++){
        id = items[i].id;
        if($(".article_item#"+id+" .article_action input").attr("checked")){
            ids.push(id);
        }
    }

    if(!ids.length)
        return;
    
    $.ajax({
        type: "post",
        url: "_product_edit_options.php",
        data: {
            id_product: ids,
            num_products: ids.length
        },
        success: function(data,code){
            $("#popup").html(data);
            $(document).keyup(function(event){ 
                if(event.keyCode == 27){
                    $("#popup").hide();
                    $("body").css("background-color", "#fff"); 
                }
            }) 
        },
        beforeSend: function(){   
            $("#popup").show();  
            $("#popup").html("<img style='display: block; margin: 25% auto;' src='img/ajax-loader.gif'/>");
            $("body").css("background-color", "#ddd");            
            $(document).keyup(function(event){ 
                if(event.keyCode == 27){
                    $("#popup").hide();
                    $("body").css("background-color", "#fff"); 
                }
            }) 
        }
    });
                                                                                                
}       
                                                           
   

function saveSelectedProduct(){
    var id=0;
    var items = $(".article_item");
    for(var i = 0; i < items.length; i++){
        id = items[i].id;
        if($(".article_item#"+id+" .article_action input").attr("checked"))
            save_form(id);
    }
}

function deleteSelectedProduct(){
    var id=0;
    var items = $(".article_item");
    for(var i = 0;  i < items.length; i++){
        id = items[i].id;
        if($(".article_item#"+id+" .article_action input").attr("checked"))
            delete_form(id);
    }
}

function undeleteSelectedProduct(){    
    var id=0;
    var items = $(".article_item");
    for(var i = 0;  i < items.length; i++){
        id = items[i].id;
        if($(".article_item#"+id+" .article_action input").attr("checked"))
            undelete_form(id);
    }
}

function priceTagSelectedProduct(){
    var id=0;
    var items = $(".article_item");
    for(var i = 0; i < items.length; i++){
        id = items[i].id;
        if($(".article_item#"+id+" .article_action input").attr("checked"))
            price_tag_add(id);
    }
}

function product_toggle_selection(id){
    if($(".article_item#"+id+" .article_action input").is(":checked"))    
        product_select(id);   
    else     
        product_deselect(id); 
}

function product_select(id_product){     
    $(".article_item#"+id_product+" .article_action input").attr("checked","checked");
    $(".article_item#"+id_product).css('border-color', 'red');
}

function product_deselect(id_product){       
    $(".article_item#"+id_product+" .article_action input").removeAttr("checked");
    $(".article_item#"+id_product).css('border-color', '#ccc'); 
}


// определяет локальное состояние продукта и решает, открывать его или закрывать
function product_toggle(id){
    // если подробная информация показана, то свернуть, иначе развернуть
    if( $(".article_item#"+id+" .info").is(":visible"))
        product_hide(id);            
    else
        product_show(id);            
}
// скрыть подробную информацию о продукте
function product_hide(id){
    $(".article_item#"+id+" .info").hide();
    $(".article_item#"+id+" .product_buttons").hide(); 
    $(".article_item#"+id).css("width", "182");
    $(".article_item#"+id).css("margin-right", "4px");
    $(".article_item#"+id).css("background-color", "#e5e5e5");
}

// показать подробную информацию о продукте
function product_show(id){
    $(".article_item#"+id+" .info").show();
    $(".article_item#"+id+" .product_buttons").show();              
    $(".article_item#"+id).css("clear", "both");
    $(".article_item#"+id).css("float", "none");
    $(".article_item#"+id).css("width", "782");
    $(".article_item#"+id).css("margin-right", "0");
    $(".article_item#"+id).css("background-color", "transparent");                    
}

// Функция обновляет представление продуктов в соответствии с ключом is_visible
function refresh(){
    for(var i = 0; i < $(".article_item").length; i++){
        var id = $(".article_item")[i].id;
        if(is_visible){
            $("#show_hide").html("Свернуть все");
            product_show(id);
        }
        else{                    
            $("#show_hide").html("Развернуть все");
            product_hide(id);
        }
    }
}

function refresh_selection(){
    for(var i = 0; i < $(".article_item").length; i++){
        var id = $(".article_item")[i].id;
       
        if(is_selected){
            $("#select_deselect").html("Снять выделение");
            product_select(id);
        }
        else{                    
            $("#select_deselect").html("Выделить все");
            product_deselect(id);
        }      
    }
}
function joinProducts(){    
    var checkedItems = [];
    var items = $("input[name=id_product]").toArray();
    for(var i = 0; i < items.length; i++){
        if(items[i].checked)
            checkedItems.push(items[i].value);
    }
    
    if(checkedItems.length < 2)
        return;
    
    
    $.ajax({
        type: "post",
        url: "product_join.php",
        data: {
            id: checkedItems,
            ajax: 1
        },
        success: function(data, code)
        {            
            for(var i = 0; i < checkedItems.length; i++)
                $('.article_item#'+checkedItems[i]).remove();
            $("#search_result").prepend(data);
        },
        beforeSend: function(){           
        }
    }); 

}

function save_form( id_product ){    
    var form = document.forms['form'+id_product];
    var elems = form.elements;
    
    var id_catalog = [];
   
    for(var i = 0; i < elems['id_catalog[]'].length; i++){
        if(elems['id_catalog[]'][i].checked)           {
            id_catalog.push(elems['id_catalog[]'][i].value);
        }
    }
    
    $.ajax({
        type: "post",
        url: "product_edit.php",
        data: {
            id_product: id_product,
            id_catalog: id_catalog,
            name: elems['name'].value,
            cost: elems['cost'].value,
            existence: elems['existence'].value,
            discount: elems['discount'].value,
            id_product_unit: elems['id_product_unit'].value,
            visible: elems['visible'].checked,
            description: elems['description'].value,
            ajax: 1
        },
        success: function(data, code)
        {
            $('#load'+id_product).show(); 
            if('success' == code )
            {
                var code = parseInt(data, 10);
                if(code == 1)
                {
                    $('#load'+id_product).css("background-color", "#cbe7A0");
                    $('#load'+id_product).css("padding", "4px");
                    $('#load'+id_product).html("Сохранение: Успешно!");
                }
                
                else if(code === -1)
                {
                    $('#load'+id_product).css("background-color", "red");
                    $('#load'+id_product).css("padding", "4px");
                    $('#load'+id_product).html("Сохранение: Ошибка доступа!");                         
                }
                
                else 
                {
                    $('#load'+id_product).css("background-color", "red");
                    $('#load'+id_product).css("padding", "4px");
                    $('#load'+id_product).html("Сохранение: Ошибка!"); 
                    alert(data)
                }
            }
        },
        beforeSend: function(){
            $('#load'+id_product).hide();            
        }
    }); 
}

function undelete_form( id_product ){
    $.ajax({
        type: "get",
        url: "product_undelete.php",
        data: {
            id_product: id_product,
            ajax: 1
        },
        success: function(data, code)
        {
            $('#load'+id_product).show(); 
            if('success' == code )
            {
                var ret = parseInt(data, 10);
                if(ret == 1)
                {
                    $('#load'+id_product).css("background-color", "#cbe7A0");
                    $('#load'+id_product).css("padding", "4px");
                    $('#load'+id_product).html("Товар восстановлен!");
                }
                
                else if(ret === -1)
                {
                    $('#load'+id_product).css("background-color", "red");
                    $('#load'+id_product).css("padding", "4px");
                    $('#load'+id_product).html("Ошибка доступа!");                         
                }
                
                else 
                {
                    $('#load'+id_product).css("background-color", "red");
                    $('#load'+id_product).css("padding", "4px");
                    $('#load'+id_product).html("Ошибка!"); 
                    alert(data)
                }
            }
        },
        beforeSend: function(){
            $('#load'+id_product).hide();       
        }
    });
}
function delete_form( id_product )
{   
    $.ajax({
        type: "get",
        url: "product_delete.php",
        data: {
            id_product: id_product,
            ajax: 1
        },
        success: function(data, code)
        {
            $('#load'+id_product).show(); 
            if('success' == code )
            {
                var ret = parseInt(data, 10);
                if(ret == 1)
                {
                    $('#load'+id_product).css("background-color", "red");
                    $('#load'+id_product).css("padding", "4px");
                    $('#load'+id_product).html("Товар удален!");
                    $('.article_item#'+id_product).hide();
                }
                
                else if(ret === -1)
                {
                    $('#load'+id_product).css("background-color", "red");
                    $('#load'+id_product).css("padding", "4px");
                    $('#load'+id_product).html("Ошибка доступа!");                         
                }
                
                else 
                {
                    $('#load'+id_product).css("background-color", "red");
                    $('#load'+id_product).css("padding", "4px");
                    $('#load'+id_product).html("Ошибка!"); 
                    alert(data)
                }
            }
        },
        beforeSend: function(){
            $('#load'+id_product).hide();
        
        }
    });
}

function deleteComplete_form( id_product )
{   
    $.ajax({
        type: "get",
        url: "product_deleteComplete.php",
        data: {
            id_product: id_product,
            ajax: 1
        },
        success: function(data, code)
        {
            $('#load'+id_product).show(); 
            if('success' == code )
            {
                var ret = parseInt(data, 10);
                if(ret == 1)
                {
                    $('.article_item#'+id_product).hide();
                }
                
                else if(ret === -1)
                {
                    $('#load'+id_product).css("background-color", "red");
                    $('#load'+id_product).css("padding", "4px");
                    $('#load'+id_product).html("Ошибка доступа!");                         
                }
                
                else 
                {
                    $('#load'+id_product).css("background-color", "red");
                    $('#load'+id_product).css("padding", "4px");
                    $('#load'+id_product).html("Ошибка!"); 
                    alert(data)
                }
            }
        },
        beforeSend: function(){
            $('#load'+id_product).hide();
        
        }
    });
}







function turnover(id_turnover_type, id_product){
    
    var form = document.forms['turnover_'+id_turnover_type+'_'+id_product];
    var elems = form.elements;
    $.ajax({
        type: "post",
        url: "turnover.php",
        data: {
            id_product: id_product,
            id_turnover_type: id_turnover_type,
            quantity: elems['quantity'].value,
            ajax:1
        },
        success: function(data, code)
        {
            $('#load'+id_product).show(); 
            var code = parseInt(data, 10);
            if(code == 1)
            {
                $('#load'+id_product).css("background-color", "#cbe7A0");
                $('#load'+id_product).css("padding", "4px");
                $('#load'+id_product).html("Оборот_"+id_turnover_type+": Успешно!");
                
                var form = document.forms['form'+id_product];
                // если оборот - это покупка, то вычитать кол-во, иначе прибавлять
                var existence = 0;
                if(form.elements['existence'].value != undefined && !isNaN(form.elements['existence'].value) && form.elements['existence'].value.length)
                    existence = form.elements['existence'].value;
                if(id_turnover_type != 1){
                    existence = Number(parseFloat( existence ) - parseFloat(elems['quantity'].value)).toPrecision(4)
                    form.elements['existence'].value = existence;
                }
                else{
                    existence = Number(parseFloat( existence ) + parseFloat(elems['quantity'].value)).toPrecision(4)
                    form.elements['existence'].value = existence;
                }
            
            }
            
            else if(code === -1)
            {
                $('#load'+id_product).css("background-color", "red");
                $('#load'+id_product).css("padding", "4px");
                $('#load'+id_product).html("Оборот_"+id_turnover_type+": Ошибка доступа!");                         
            }
            
            else if(code === 0)
            {
                $('#load'+id_product).css("background-color", "red");
                $('#load'+id_product).css("padding", "4px");
                $('#load'+id_product).html("Оборот_"+id_turnover_type+": Нет такого количества!"); 
            }
        },
        beforeSend: function(){
            $('#load'+id_product).hide();            
        }
    });
}



function set_cover(id_product_picture){
    var form = document.forms['product_picture'+id_product_picture];
    var elems = form.elements;
    
    $.ajax({
        type: "post",
        url: "product_picture.php",
        data: {
            id_product: elems['id_product'].value,
            id_product_picture: id_product_picture,
            action: "cover",
            ajax: 1
        },
        success: function(data, code)
        {      
            if('success' == code && parseInt(data, 10) == 1){
                $("form.left").css("border","1px solid #e5e5e5");                
                form.style.border = "1px solid #3e809d";
            }
        }
    });
}

function product_picture_delete(id_product_picture){
    var form = document.forms['product_picture'+id_product_picture];
    var elems = form.elements;
    
    $.ajax({
        type: "post",
        url: "product_picture.php",
        data: {
            id_product: elems['id_product'].value,
            id_product_picture: id_product_picture,
            action: "delete",
            ajax: 1
        },
        success: function(data, code)
        {            
            if('success' == code && parseInt(data, 10) == 1){
                form.style.display = "none";
            }
        }
    });
}
            