/**
 * Created by Salorium on 06/12/13.
 */
Debug.controller =  {

    init: function (debugged) {
        that = Debug;
        that.model.debugged = debugged;
        //Réglage de la hauteur
        if (that.model.debugged) {
            $("div.debugger-data").height($(window).height() * 0.7);
            if (!that.model.listenered) {
                that.view.listener();
                that.view.resize();
                that.model.listenered = true;
            }
        }
    }
};