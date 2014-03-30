/**
 * Created with JetBrains PhpStorm.
 * User: Salorium
 * Date: 27/09/13
 * Time: 13:03
 * To change this template use File | Settings | File Templates.
 */
$(document).on('click', ".debugger-deroule", function (event) {
    event.preventDefault();
    var a = $(this);
    if ($("#"+a.data("id")).is(":visible")){
        $("#"+a.data("id")).hide(100);

    }else{
        $("#"+a.data("id")).show();
    }
    console.info("tt");
});

var Debugger ={
    show : function (){
        $(".debugger").show();
        $.ajax({
            type: "POST",
            contentType: "application/json",
            url: '/index2.php',
            data: { name: 'norm' },
            dataType: "json"
        });
    },
    close : function(){
        $("#debugger").hide();
    }
};

