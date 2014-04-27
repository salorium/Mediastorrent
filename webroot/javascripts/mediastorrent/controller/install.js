/**
 * Created by salorium on 15/03/14.
 */
Install.controller =  {
    enableModule : function(e){
        formData = new FormData($("#root")[0]);
        formData.append("action", $(e).attr("data-module"));
        $(e).html("<span style='color: orange'>En cour d\'installation</span>");
        $.ajax({
            url: Base.controller.makeUrlBase()+'install/enableModule.json',
            async : false,
            dataType :"json",
            type: "post",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function(response, textStatus, jqXHR){
                //afficheResultat(container,response);

            },
            error: function(jqXHR, textStatus, errorThrown){
                // afficheErreur(jqXHR.responseText,container);
            }

        });
        $.ajax({
            url: Base.controller.makeUrlBase()+'install/checkModule/'+$(e).attr("data-module")+'.json',
            async : false,
            dataType :"json",
            cache :false,
            type: "get",
            success: function(response, textStatus, jqXHR){
                //afficheResultat(container,response);
                $(e).html((response.extension ? "<span style='color: green'>Ok</span>":"<span style='color: red'>Non ok</span>"));
                if ( response.extension){
                    $(e).removeAttr("onclick");
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                // afficheErreur(jqXHR.responseText,container);
            }

        });
    },
    enableWriteFile : function(e){
        formData = new FormData($("#root")[0]);
        var el = $(e).attr("data-filewrite");
        formData.append("file", el);
        $(e).html("<span style='color: orange'>En cour</span>");
        $.ajax({
            url: Base.controller.makeUrlBase()+'install/enableWriteFile.json',
            async : false,
            //dataType :"json",
            type: "post",
            contentType: false,
            processData: false,
            data: formData,
            dataType :"json",
            success: function(response, textStatus, jqXHR){
                //afficheResultat(container,response);
                $(e).html((response.ecriture ? "<span style='color: green'>Ok</span>":"<span style='color: red'>Non ok</span>"));
                if ( response.ecriture){
                    $(e).removeAttr("onclick");
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                // afficheErreur(jqXHR.responseText,container);
            }

        });
    }

};