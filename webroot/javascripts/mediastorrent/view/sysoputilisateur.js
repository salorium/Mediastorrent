/**
 * Created by salorium on 07/05/14.
 */
Sysoputilisateur.view = {
    fixedHeightContenu: function (hauteur) {
        Base.view.fixedHeight("#contenu", hauteur);
    },
    ajouteUtilisateur: function () {
        var opt = '';
        $.each(Sysoputilisateur.model.role, function (k, v) {
            opt += '<option value="' + v + '">' + v + '</option>';
        });
        res = ' <form id="ajouterUtilisateur" method="post" enctype="multipart/form-data" onsubmit="Sysoputilisateur.controller();">' +
            '<div class="row expansion">' +
            '    <div class="large-3 columns">' +
            '<label for="adjlogin" class="text-center inline">Login : </label>' +
            '</div>' +
            '<div class="large-9 columns">' +
            '    <input type="text" name="login" id="adjlogin" />' +
            '</div>' +
            '</div>' +
            '<div class="row expansion">' +
            '    <div class="large-3 columns">' +
            '<label for="role" class="text-center inline">Rôle : </label>' +
            '</div>' +
            '<div class="large-9 columns">' +
            '    <select name="role" data-invalid="" id="role" class="medium" required="">' + opt +
            '</select>' +
            '</div>' +
            '</div>' +
            '<div class="row expansion">' +
            '    <div class="large-3 columns">' +
            '<label for="mail" class="text-center inline">Mail : </label>' +
            '</div>' +
            '<div class="large-9 columns">' +
            '    <input type="text" name="mail" id="mail"/>' +
            '</div>' +
            '</div>' +
            '<div class="row">' +
            '<div class="small-2 small-centered columns">' +
            '<button class="button small secondary expand" type="submit">Ajouter</button>' +
            '</div>' +
            '</div></form>';
        Base.view.boxmodal.make("Ajouter un utilisateur", res);
    }
};