/**
 * Created by salorium on 15/03/14.
 */
Base.view =  {
    showRedirection : function (){
        $(".container").append("<h2>Redirection dans <span id='redi'>"+Base.model.redirection.compteur+"</span> seconde.</h2>");
    },
    updateCptRedirection : function (){
        $("#redi").html(Base.model.redirection.compteur);
    },
    fixedHeightContainer : function(){
        this.hauteurWindows = $(window).height();
        Base.view.fixedHeight(".container",this.hauteurWindows-75);
    },
    fixedHeight : function(container,hauteur){
        $(container).css("height",hauteur);
    },
    noty : {
        generate : function (type,texte){
            var n = noty({
                text        : texte,
                type        : type,
                dismissQueue: true,
                layout      : 'bottomRight',
                theme       : 'defaultTheme',
                timeout: 2000
            });
        },
        generateConfirm :function(texte,call_oui,call_non){
            var n = noty({
                text        : texte,
                type        : 'alert',
                dismissQueue: true,
                layout      : 'center',
                theme       : 'defaultTheme',
                buttons     : [
                    {addClass: 'btn btn-primary', text: 'Oui', onClick: function ($noty) {
                        $noty.close();
                        if ( call_oui)
                        call_oui();
                    }
                    },
                    {addClass: 'btn btn-danger', text: 'Non', onClick: function ($noty) {
                        $noty.close();
                        if ( call_non)
                        call_non();
                    }
                    }
                ]
            });
        }
    }
};