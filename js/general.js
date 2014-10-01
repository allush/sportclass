  
  

function my_resize(){ 
    $("#spring").height(0); 
    var h_window = $(window).height();
    var h_wrapper =  $("#wrapper").outerHeight(); 
    var h_footer  =  $("#footer").outerHeight();
        
    var h_spring = 0;
    h_spring = h_window - h_wrapper - h_footer - 16;                      
    if(h_spring > 0 )
        $("#spring").height(h_spring);
    
}
  
// id_catalog - идентификатор каталога, из которого
// нужно выбрать товары
// num_default_loaded_rows - кол-во строк, загруженных по-умолчанию
  
function load_products(id_catalog){  
    // количество подгружаемых товаров
    var part = 12;
    //верхняя граница первого товара
    var top_first_product = $(".product_item:first").offset().top;
    // высота блока товара
    var h_first_product = $(".product_item:first").outerHeight();                    
                    
    // край видимой области                        
    var view_point =  $(window).scrollTop() + $(window).height();
                   
    // количество прогруженных товаров
    var num_loaded_products = 3 * (1+parseInt(((view_point-top_first_product) / h_first_product)));
        
    if(num_loaded_products <= num_default_loaded_products)
        return;
    
    $.ajax({
        type: "post",
        url: "product_load.php",
        data: { 
            id_catalog: id_catalog,
            from: num_default_loaded_products,
            part: part
        },
        success: function(data, code){
            
            $("#loader").remove();
            $("#product_load").append(data);
            Shadowbox.setup();// обновить список картинок, полежащих загрузке shadowbox
        },
        beforeSend: function(){
            $("#product_load").append("<div id='loader' class='clearer'><br><img style='display: block; margin: 4px auto;' src='img/ajax-loader.gif' /></div>");     
   }
    });
    
    num_default_loaded_products += part;
}


// id_catalog - идентификатор каталога, из которого
// нужно выбрать товары
// num_default_loaded_rows - кол-во строк, загруженных по-умолчанию
  
function load_video(){ 
    var part = 5;
    //верхняя граница первого товара
    var top_first_product = $(".video_item:first").offset().top;
    // высота блока товара
    var h_first_product = $(".video_item:first").outerHeight();                    
                    
    // край видимой области                        
    var view_point =  $(window).scrollTop() + $(window).height();
                   
    // количество прогруженных товаров
    var num_loaded_products = (1+parseInt(((view_point-top_first_product) / h_first_product)));
        
        
    if(num_loaded_products <= num_default_loaded_video)
        return;
    
    $.ajax({
        type: "post",
        url: "video_load.php",
        data: { 
            from: num_default_loaded_video,
            part: part
        },
        success: function(data, code){
            if(data == "0"){
                $("#product_load").html("");
                return;
            }
            $("#product_load").html("");
            $("#spring").append(data);
        },
        beforeSend: function(){
            $("#product_load").html("<div class='clearer'><br><img style='display: block; margin: 4px auto;' src='img/ajax-loader.gif' /></div>");
        }
    });
    num_default_loaded_video += part;
}

function tbutton(){
    $("#tbutton").hide();
    if($(window).scrollTop() > $(window).height()){
        $("#tbutton").show();
        
        var x = $("#center").offset().left - $("#tbutton").outerWidth() - 4;
        $("#tbutton").css("left",x+"px");
        $("#tbutton").css("top",$(window).height()-$("#tbutton").outerHeight()-200 + $(window).scrollTop());
    }
}

function search_product(keyword){
    if(event.keyCode == 27){
        $("input[name=keyword]").attr("value","")
        $(".search .result_search").html("");
    }
                        
    if(event.which != 13)
        return;            
                                                                                                                
    if(!keyword.length){
        $(".search .result_search").html("");
        return;
    }
    $.ajax({
        type: "post",
        url: "product_search.php",
        data: {
            ajax:1,
            keyword: keyword
        },
        success: function(data, code)
        {
            if("success" == code){                             
                                                                               
                if(data == 0)
                    $(".search .result_search").html("<p id='close'>Скрыть результаты поиска</p> По запросу<i>\""+keyword+"\"</i> ничего не найдено!");
                else {
                    $(".search .result_search").html("<p id='close'>Скрыть результаты поиска</p>"+data); 
                    Shadowbox.setup();// обновить список картинок, полежащих загрузке shadowbox
                }   
                $("#close").click(function(){
                    $(".search .result_search").hide();
                    $("input[name=keyword]").attr("value","")
                })  
            }
        },
        beforeSend: function(){ 
            $(".search .result_search").show();
            $(".search .result_search").html("<p style='padding: 8px 0;'><img src='../img/ajax-loader.gif' /></p>");
        }
    });
}