/**
 * Created by Salorium on 06/12/13.
 */
Debug.view =  {
    show: function () {
        $("#debugger").show();
        /*$.ajax({
         type: "POST",
         contentType: "application/json",
         url: '/index2.php',
         data: { name: 'norm' },
         dataType: "json",
         success: function(response, textStatus, jqXHR){
         Debugger.updateDebugger(response);
         },
         error: function(jqXHR, textStatus, errorThrown){
         alert(jqXHR.responseText+" "+textStatus);
         }
         });*/
    },
    close: function () {
        $("#debugger").hide();
    },
    listener: function () {
        $(document).on('click', ".debugger-deroule", function (event) {
            event.preventDefault();
            var a = $(this);
            if ($("#" + a.data("id")).is(":visible")) {
                $("#" + a.data("id")).hide(100);

            } else {
                $("#" + a.data("id")).show();
            }
        });


    },
    resize: function () {
        var o = Debug;
        $(window).resize(function () {
            o.controller.init(o.model.debugged);
        });
        window.onerror = function (msg, url, line) {
            //msg = "JS error: [" + url + " : " + line + "] " + msg;
            setTimeout(function(){
                o.view.addErreurJs(msg,url,line);
            },1);
            //alert(msg);
            return true;
        }
    },
    changeIcon: function (name){
        $("#debuggericon").attr("src", "http://mediastorrent/images/debugger"+name+".svg");
    },
    addErreurJs: function (msg,url,line){
        var that = Debug;
        if (that.model.jserror == null){
            $("#debugger-js").empty();
            $("#debugger-js").html('<fieldset class="bleu"><legend class="debugger-deroule" data-id="j1">Javascript Erreur (<span id="debugger-data-nb-js">0</span>)</legend>' +
                '<div id="j1" class="debugger-auto">'+
                '<table class="debugger">' +
                '<thead><tr><th>Fichier</th><th width="50">Ligne</th><th>Message</th></tr></thead><tbody id="debugger-data-js"></tbody></table></div></fieldset>');
            that.model.jserror = true;
        }
        cterreur = parseInt($("#debugger-data-nb-js").text())+1;
        $("#debugger-data-nb-js").html(cterreur);
        $("#debugger-data-js").append("<tr><td>"+url+"</td><td>"+line+"</td><td>"+msg+"</td></tr>");
        that.view.changeIcon("erreur");


    }
};

