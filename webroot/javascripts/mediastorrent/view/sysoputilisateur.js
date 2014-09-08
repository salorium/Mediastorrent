/**
 * Created by salorium on 07/05/14.
 */
Sysoputilisateur.view = {
    fixedHeightContenu: function (hauteur) {
        Base.view.fixedHeight("#contenu", hauteur);
    },
    ajouteUtilisateur: function () {
        res = ' <form id="ajouterUtilisateur" method="post" enctype="multipart/form-data" onsubmit="Sysoputilisateur.controller();">' +
            '<div class="row expansion">' +
            '    <div class="large-3 columns">' +
            '<label for="trackers" class="text-center inline">Login : </label>' +
            '</div>' +
            '<div class="large-9 columns">' +
            '    <input type="text" name="login" />' +
            '</div>' +
            '</div>' +
            '<div class="row">' +
            '<div class="small-2 small-centered columns">' +
            '<button class="button small secondary expand" value="create" type="submit">Modifier</button>' +
            '</div>' +
            '</div></form>';
        Base.view.boxmodal.make("Ajouter un utilisateur", res);
    }
};