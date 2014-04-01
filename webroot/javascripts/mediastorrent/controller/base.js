/**
 * Created by salorium on 15/03/14.
 */
Base.controller =  {
    tableScroll: function(){
        var $tables = $("table.scroll");
        $.each( $tables, function(k,v){
            $bodyCells = $(v).find('tbody tr:first').children();
            colWidth = $bodyCells.map(function() {
                return $(this).width();
            }).get();
            $(v).find('thead tr').children().each(function(i, vv) {
                //console.log($(vv).css("width"));
                //if(Base.model.converter.iv($(vv).css("width")) < colWidth[i])
                $(vv).width(colWidth[i]);
            });

        });
        console.log($tables);
    },
    setUtilisateur : function(args){
        Base.model.utilisateur.login = args[0];
        Base.model.utilisateur.keyconnexion = args[1];
    },

    fixeHeightContainer: function(){
        Base.view.fixedHeightContainer();
    },
    redirection: function (url) {
        Base.model.redirection.compteur = 5;
        Base.view.showRedirection();
        Base.model.redirection.timer = setInterval( function(){
            Base.model.redirection.compteur --;
            Base.view.updateCptRedirection();
            if ( Base.model.redirection.compteur == 0){
                clearInterval(Base.model.redirection.timer);
                $(location).attr('href',url);
            }
        },1000);
    }
};