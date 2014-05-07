/**
 * Created by salorium on 07/05/14.
 */
Sysoputilisateur.controller = {
    init: function () {
        var hauteur = Base.model.conf.containerHeight() - Base.model.html.hauteur(".container nav");
        Sysoputilisateur.view.fixedHeightContenu(hauteur);
    }
}