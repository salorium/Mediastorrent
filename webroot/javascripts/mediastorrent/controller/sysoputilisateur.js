/**
 * Created by salorium on 07/05/14.
 */
Sysoputilisateur.controller = {
    init: function () {
        var hauteur = Base.model.conf.containerHeight() - Base.model.html.hauteur(".container nav");
        Sysoputilisateur.view.fixedHeightContenu(hauteur);
    },
    updateUser: function (element) {
        $('#updateuser').append("<input type='hidden' name='login' value='" + $(element).html() + "'>");
        $('#updateuser').submit();
    },
    setRole: function (args) {
        Sysoputilisateur.model.role = args;
    },
    ajouterUtilisateur: function (e) {
        e.preventDefault();
        var formData = new FormData($("#ajouterUtilisateur")[0]);

        $.ajax({
            url: Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'utilisateur/create.json',
            async: false,
            //dataType :"json",
            type: "post",
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (response, textStatus, jqXHR) {
                //afficheResultat(container,response);
                if (response.res != null) {
                    $("#createTorrentContenu").hide();
                    $("#createToto").show();
                    Torrent1.model.createTorrent.noTask = response.res.no;
                    Torrent1.controller.createTorrent.check(response.res.no);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                // afficheErreur(jqXHR.responseText,container);
                Base.view.noty.generate("error", "Impossible de se connecter");
            }

        });
    }
}