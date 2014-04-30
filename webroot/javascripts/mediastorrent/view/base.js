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
    image: {
        input : function(name,id,container){
            $("#"+container).append('<label for="'+id+'">'+name+' :</label>');
            var $poster = $('<input class="large-2" type="text" name="'+id+'" id="'+id+'">');
            $("#"+container).append($('<div class="row"></div>').append($('<div class="large-12"></div>').append($poster)));
            var $img = $('<img height="300px" src="'+Base.controller.makeUrlBase()+"proxy/imageSetHeight/non/300.jpg"+'">');
            $("#"+container).append($img);
            $poster.on("change keyup update input", function() {
                if ($.trim($poster.val()).length > 1){
                    $img.attr("src",Base.controller.makeUrlBase()+"proxy/imageSetHeight/"+Base.model.converter.paramUrl($poster.val())+"/300.jpg");
                }else{
                    $img.attr("src",Base.controller.makeUrlBase()+"proxy/imageSetHeight/non/300.jpg");
                }
            });
            $poster.on("paste", function(){
                setTimeout(function(){
                    $poster.change();
                },1);
            })
        }
    },
    noty : {
        generate : function (type,texte,layout){
            var n = noty({
                text        : texte,
                type        : type,
                dismissQueue: true,
                layout      : (layout ? layout:'bottomRight'),
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