/**
 * Created by salorium on 15/03/14.
 */
Base.controller =  {
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