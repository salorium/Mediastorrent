/**
 * Created by salorium on 15/03/14.
 */
Torrent1.controller = {
    init: function (seedbox) {
        //Hide du panneau d'ajout d'un torrent

        //Fixation de la hauteur du panneaux contenu
        var hauteur = Base.model.conf.containerHeight() - Base.model.html.hauteur(".container nav");
        Torrent1.view.fixedHeightContenu(hauteur);
        this.loader.init();
        this.seedbox.init(seedbox);
        this.listTorrent.init();
        this.detailsTorrent.init();
        this.filesTorrent.init();
        this.trackersTorrent.init();
        this.addTorrent.init();
        this.createTorrent.init();
        Torrent1.view.addTorrent.hide();
        Torrent1.view.createTorrent.hide();
    },
    seedbox: {
        init: function (seedbox) {
            if (seedbox.length > 0) {
                Torrent1.model.baseUrl = seedbox[0].hostname;
                Torrent1.model.nomseedbox = seedbox[0].nom;
                Torrent1.model.seedbox.seedboxs = seedbox;
                Torrent1.view.seedbox.init(0);
                this.update("");
            } else {
                Base.view.noty.generate("information", "Aucune seedbox n'est associée à votre compte !", "center")
            }
        },
        reload: function (id) {
            Torrent1.view.seedbox.init(id);
            Torrent1.model.seedbox.changed = true;
            Torrent1.controller.listTorrent.resetSelectionne();
            Torrent1.controller.filesTorrent.reset();
            if (Torrent1.model.errorServer) {
                this.update("");
                Torrent1.model.errorServer = false;
            }
            //this.update("");
        },
        update: function (cid) {
            var url = Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'torrent/liste/' + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion + "/" + cid;
            if (Torrent1.model.listTorrent.selectionne.length == 1) {
                url += "/" + Torrent1.model.listTorrent.selectionne[0];
            }
            $.ajax({
                url: url + ".json",
                dataType: "json",
                //contentType: "application/json",
                success: function (response, textStatus, jqXHR) {
                    if (response.host === Torrent1.model.baseUrl) {
                        Torrent1.view.loaders.hideListeTorrent();
                        if (response.showdebugger === "ok") {
                            var res = response.torrent;
                            torrent = res[0];
                            Torrent1.view.listTorrent.stats(res[2], res[3], res[4]);
                            Torrent1.controller.listTorrent.conversion(torrent);
                            if ($("#recherche").val().length > 1) {
                                Torrent1.controller.listTorrent.recherche();
                            } else {
                                Torrent1.controller.listTorrent.resetRecherche();
                            }
                            Torrent1.controller.listTorrent.affiche();
                            if (response.hashtorrent == Torrent1.model.listTorrent.selectionne[0]) {
                                if (response.torrentselectionnee) {
                                    if (response.torrentselectionnee.files) {
                                        var t = Torrent1.model.listTorrent.changed && response.torrentselectionnee.files != [] && response.torrentselectionnee.detail != [];
                                        Torrent1.controller.detailsTorrent.conversion(response.torrentselectionnee.detail, t);
                                        Torrent1.controller.filesTorrent.conversion(response.torrentselectionnee.files, t);
                                        Torrent1.controller.trackersTorrent.conversion(response.torrentselectionnee.trackers, t);
                                        Torrent1.model.listTorrent.changed = false;
                                    }

                                } else {
                                    var t = true;
                                    Torrent1.controller.detailsTorrent.conversion([], t);
                                    Torrent1.controller.filesTorrent.conversion([], t);
                                }
                            }
                            Torrent1.view.detailsTorrent.affiche();
                            Torrent1.view.filesTorrent.afficheArbre();
                            Torrent1.view.trackersTorrent.afficheTrackers();
                            Torrent1.model.seedbox.changed = false;
                            setTimeout(function () {
                                Torrent1.controller.seedbox.update(res[1]);
                            }, 1000);
                        } else {
                            Torrent1.controller.listTorrent.resetSelectionne();
                            Torrent1.controller.filesTorrent.reset();
                            Torrent1.controller.detailsTorrent.reset();
                            $("#btdetails").parent().children().removeClass('active');
                            $("#btdetails").addClass('active');
                            $("#panel2-1").parent().children().removeClass('active');
                            $("#panel2-1").addClass('active');
                            Torrent1.model.container.listtorrent.empty();
                            Torrent1.model.seedbox.changed = false;
                            Torrent1.view.detailsTorrent.affiche();
                            Torrent1.view.filesTorrent.afficheArbre();

                            Base.view.noty.generate("error", "Impossible de se connecter à rtorrent");
                            setTimeout(function () {
                                Torrent1.controller.seedbox.update("");
                            }, 10000);
                        }
                    } else {
                        setTimeout(function () {
                            Torrent1.controller.seedbox.update("");
                        }, 100);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Torrent1.view.loaders.hideListeTorrent();
                    Torrent1.controller.listTorrent.resetSelectionne();
                    Torrent1.controller.filesTorrent.reset();
                    Torrent1.controller.detailsTorrent.reset();
                    $("#btdetails").parent().children().removeClass('active');
                    $("#btdetails").addClass('active');
                    $("#panel2-1").parent().children().removeClass('active');
                    $("#panel2-1").addClass('active');
                    Torrent1.model.container.listtorrent.empty();
                    Torrent1.model.seedbox.changed = false;
                    Torrent1.view.detailsTorrent.affiche();
                    Torrent1.view.filesTorrent.afficheArbre();
                    Torrent1.model.errorServer = true;
                    Base.view.noty.generate("error", "Impossible de se connecter à " + Torrent1.model.nomseedbox);
                }
            });
        }
    },
    listTorrent: {
        init: function () {
            //Assignation du conteneur listtorrent qui contienderas les torrent
            Torrent1.model.container.listtorrent = $("#listorrent");
            this.nbTorrent();
            var input = $("#recherche")[0];
            input.onupdate = input.onkeyup = function () {
                if ($.trim(input.value).length > 1) {
                    Torrent1.controller.listTorrent.recherche();
                    Torrent1.controller.listTorrent.affiche();
                } else {
                    Torrent1.controller.listTorrent.resetRecherche();
                    Torrent1.controller.listTorrent.affiche();
                }
            }
        },
        resetRecherche: function () {
            Torrent1.model.listTorrent.listerecherche = [];
        },
        nbTorrent: function () {
            //Calcul du nb de torrent max dans la liste des torrent
            var hauteurlisttorrent = $("#moitiegauche").height() - Base.model.html.hauteur("#moitiegauche dl");
            Torrent1.model.container.listtorrent.empty();
            Torrent1.model.container.listtorrent.append("<span class='bt' onclick='Torrent.controller.next(0)'>▲</span>");
            Torrent1.model.container.listtorrent.append(Torrent1.view.listTorrent.liste());
            Torrent1.model.listTorrent.nbtorrents = Math.floor(((hauteurlisttorrent - Base.model.html.hauteur("span.bt") * 2) / Base.model.html.hauteur("fieldset.torrent")));
            Torrent1.model.container.listtorrent.empty();
        },
        recherche: function () {
            var recher = new RegExp("(" + $("#recherche").val() + ")", "gi");
            Torrent1.model.listTorrent.listerecherche = [];
            console.log(Torrent1.model.listTorrent.liste);
            var liste = Torrent1.model.listTorrent.liste.clone();
            $.each(liste, function (k, v) {
                if (recher.test(v[1])) {
                    v[1] = v[1].replace(recher, '<span class="success radius label">$1</span>');
                    Torrent1.model.listTorrent.listerecherche.push(v);
                }
            });
        },
        details: function () {
            var url = Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'torrent/details/' + Torrent1.model.listTorrent.selectionne[0] + "/" + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion;
            $.ajax({
                url: url + ".json",
                dataType: "json",
                //contentType: "application/json",
                success: function (response, textStatus, jqXHR) {
                    if (response.host == Torrent1.model.baseUrl) {
                        if (response.showdebugger == "ok") {
                            if (Torrent1.model.listTorrent.selectionne.length == 1 && response.hashtorrent == Torrent1.model.listTorrent.selectionne[0]) {
                                if (response.torrentselectionnee) {
                                    //Torrent.model.torrentselectionneedetail = {};
                                    if (response.torrentselectionnee.files) {
                                        var t = true;
                                        Torrent1.controller.filesTorrent.conversion(response.torrentselectionnee.files, t);
                                        Torrent1.controller.detailsTorrent.conversion(response.torrentselectionnee.detail, t);
                                    }

                                } else {
                                    //var t = true;
                                    //Torrent.controller.conversionListeFiles([],t);
                                    //Torrent.controller.conversionListeDetails([],t);
                                }

                            }
                            Torrent1.view.detailsTorrent.affiche();
                            Torrent1.view.filesTorrent.afficheArbre();
                            //Torrent.view.filesTorrent();
                        } else {
                            Base.view.noty.generate("error", "Impossible de se connecter à rtorrent");
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Base.view.noty.generate("error", "Impossible de se connecter à " + Torrent.model.nomseedbox);
                }
            });
        },
        conversion: function (liste) {
            if (liste != null) {
                if (Torrent1.model.listTorrent.original.length == 0 || Torrent1.model.seedbox.changed) {
                    Torrent1.model.seedbox.changed = false;
                    Torrent1.model.listTorrent.original = liste;
                    Torrent1.model.listTorrent.cpt = 0;
                } else {
                    $.each(liste, function (k, v) {
                        if (v == false) {
                            delete Torrent1.model.listTorrent.original[k];
                        } else {
                            if (Torrent1.model.listTorrent.original[k]) {
                                $.each(v, function (kk, vv) {
                                    Torrent1.model.listTorrent.original[k][kk] = vv;
                                });
                            } else {
                                Torrent1.model.listTorrent.original[k] = v;
                            }
                        }
                    });
                }

                Torrent1.model.listTorrent.liste = [];
                $.each(Torrent1.model.listTorrent.original, function (k, v) {
                    Torrent1.model.listTorrent.liste[Torrent1.model.listTorrent.liste.length] = v;
                });
                //Tri par fusion si nécessaire
                this.triFusion();
            }
        },
        triFusion: function () {
            if (Torrent1.model.listTorrent.sortcolonne > -1) {
                Torrent1.model.listTorrent.liste = Base.model.tableau.triFusion(Torrent1.model.listTorrent.liste, Torrent1.model.listTorrent.sortcolonne, Torrent1.model.listTorrent.sorttype);
            }
        },
        affiche: function () {
            var listtorrent = Torrent1.model.container.listtorrent;
            listtorrent.empty();
            var liste = Torrent1.model.listTorrent.liste;
            if (Torrent1.model.listTorrent.listerecherche.length > 0) {
                liste = Torrent1.model.listTorrent.listerecherche;
            }
            //Calcul des borne
            if (Torrent1.model.listTorrent.cpt > liste.length)
                Torrent1.model.listTorrent.cpt = 0;
            if (Torrent1.model.listTorrent.cpt > 0) {
                Torrent1.model.container.listtorrent.append("<span class='bt' onclick='Torrent1.controller.listTorrent.next(" + (Torrent1.model.listTorrent.cpt - 1) + ")'>▲</span>");
            }
            var max = Torrent1.model.listTorrent.cpt + Torrent1.model.listTorrent.nbtorrents;
            if (Torrent1.model.listTorrent.cpt + Torrent1.model.listTorrent.nbtorrents > liste.length)
                max = liste.length;
            //Affichage d'une partie de la liste
            for (i = Torrent1.model.listTorrent.cpt; i < max; i++) {
                Torrent1.model.container.listtorrent.append(Torrent1.view.listTorrent.liste(liste[i], i));
            }
            //Ajout de la roulette sur la liste des torrents
            $('fieldset.torrent').bind('mousewheel DOMMouseScroll', function (e) {
                e.preventDefault();
                delta = e.originalEvent.detail;
                if (e.originalEvent.wheelDelta)
                    delta = e.originalEvent.wheelDelta * -1;
                if (delta < 0) {
                    Torrent1.model.listTorrent.cpt--;
                    if (Torrent1.model.listTorrent.cpt < 0)
                        Torrent1.model.listTorrent.cpt = 0;
                } else {
                    Torrent1.model.listTorrent.cpt++;

                    if (Torrent1.model.listTorrent.cpt + Torrent1.model.listTorrent.nbtorrents > liste.length) {
                        Torrent1.model.listTorrent.cpt = liste.length - Torrent1.model.listTorrent.nbtorrents;
                    }
                    if (Torrent1.model.listTorrent.cpt < 0)
                        Torrent1.model.listTorrent.cpt = 0;
                }
                Torrent1.controller.listTorrent.affiche();
            });
            $('fieldset.torrent').mousedown(function (e) {
                e.preventDefault();

                switch (e.which) {
                    case 1:
                        if (e.shiftKey) {

                            Torrent1.model.listTorrent.selectionne = [];
                            $(".torrent").removeClass("torrentselect");
                            if (Torrent1.model.listTorrent.selectionneid > -1) {
                                max = Base.model.converter.iv($(e.currentTarget).attr("idcpt"));
                                i1 = Base.model.converter.iv(Torrent1.model.listTorrent.selectionneid);
                                if (i1 < max) {
                                    for (i = i1; i < max; i++) {

                                        Torrent1.model.listTorrent.selectionne.push(liste[i][27]);
                                        $("#" + liste[i][27]).addClass("torrentselect");
                                    }
                                } else {
                                    for (i = i1; i > max; i--) {
                                        Torrent1.model.listTorrent.selectionne.push(liste[i][27]);
                                        $("#" + liste[i][27]).addClass("torrentselect");
                                    }
                                }
                                Torrent1.model.listTorrent.selectionne.push($(e.currentTarget).attr("id"));
                            } else {
                                Torrent1.model.listTorrent.selectionne.push($(e.currentTarget).attr("id"));
                                Torrent1.model.listTorrent.selectionneid = ($(e.currentTarget).attr("idcpt"));
                            }
                            Torrent1.controller.detailsTorrent.reset();
                            Torrent1.controller.filesTorrent.reset();
                            Torrent1.view.detailsTorrent.affiche();
                            Torrent1.view.filesTorrent.afficheArbre();
                            Torrent1.model.listTorrent.changed = true;
                            $(e.currentTarget).addClass("torrentselect");
                            //  mon action
                        } else if (e.ctrlKey) {
                            id = $.inArray($(e.currentTarget).attr("id"), Torrent1.model.listTorrent.selectionne)
                            if (id > -1) {
                                $(e.currentTarget).removeClass("torrentselect");
                                Torrent1.model.listTorrent.selectionne.splice(id, 1);
                            } else {
                                Torrent1.model.listTorrent.selectionne.push($(e.currentTarget).attr("id"));
                                $(e.currentTarget).addClass("torrentselect");
                            }
                            Torrent1.controller.detailsTorrent.reset();
                            Torrent1.controller.filesTorrent.reset();
                            Torrent1.view.detailsTorrent.affiche();
                            Torrent1.view.filesTorrent.afficheArbre();
                            Torrent1.model.listTorrent.changed = true;
                            Torrent1.model.listTorrent.selectionneid = ($(e.currentTarget).attr("idcpt"));
                        } else if (!(Torrent1.model.listTorrent.selectionne.length == 1 && Torrent1.model.listTorrent.selectionne[0] == $(e.currentTarget).attr("id") )) {
                            Torrent1.model.listTorrent.selectionne = [];
                            Torrent1.model.listTorrent.selectionne.push($(e.currentTarget).attr("id"));
                            Torrent1.model.listTorrent.selectionneid = ($(e.currentTarget).attr("idcpt"));
                            Torrent1.model.detailsTorrent.liste = Torrent1.model.listTorrent.original[$(e.currentTarget).attr("id")];
                            Torrent1.model.detailsTorrent.original = Torrent1.model.listTorrent.original[$(e.currentTarget).attr("id")];
                            Torrent1.view.detailsTorrent.affiche();
                            Torrent1.controller.filesTorrent.reset();
                            Torrent1.view.filesTorrent.afficheArbre();
                            Torrent1.controller.listTorrent.details();
                            Torrent1.model.listTorrent.changed = true;
                            $(".torrent").removeClass("torrentselect");
                            $(e.currentTarget).addClass("torrentselect");
                        }
                        break;
                    case 3:
                        e.stopPropagation();
                        if (!(Torrent1.model.listTorrent.selectionne.length == 1 && Torrent1.model.listTorrent.selectionne[0] == $(e.currentTarget).attr("id") )) {
                            Torrent1.model.listTorrent.selectionne = [];
                            Torrent1.model.listTorrent.selectionne.push($(e.currentTarget).attr("id"));
                            Torrent1.model.listTorrent.selectionneid = ($(e.currentTarget).attr("idcpt"));
                            Torrent1.model.detailsTorrent.liste = Torrent1.model.listTorrent.original[$(e.currentTarget).attr("id")];
                            Torrent1.model.detailsTorrent.original = Torrent1.model.listTorrent.original[$(e.currentTarget).attr("id")];
                            Torrent1.view.detailsTorrent.affiche();
                            Torrent1.controller.filesTorrent.reset();
                            Torrent1.view.filesTorrent.afficheArbre();
                            Torrent1.model.listTorrent.changed = true;
                            $(".torrent").removeClass("torrentselect");
                            $(e.currentTarget).addClass("torrentselect");

                        }
                        break;
                }

            });
            $('fieldset.torrent').dblclick(function (e) {
                e.preventDefault();
                $("#btdetails").parent().children().removeClass('active');
                $("#btdetails").addClass('active');
                $("#panel2-1").parent().children().removeClass('active');
                $("#panel2-1").addClass('active');

            });
            if (liste.length > Torrent1.model.listTorrent.nbtorrents) {
                var max = Torrent1.model.listTorrent.cpt + 1;
                if (max + Torrent1.model.listTorrent.nbtorrents - 1 < liste.length)
                //max = this.liste.length-3;
                    Torrent1.model.container.listtorrent.append("<span class='bt' onclick='Torrent1.controller.listTorrent.next(" + max + ")'>▼</span>");
            }
        },
        tri: function (e) {
            if ($("dd.anc").length > 0 && $("dd.anc").children().attr("sort-colonne") != $(e).attr("sort-colonne")) {
                $("dd.anc").children().children().html("");
                $("dd.anc").children().removeAttr("sort-type");
                $("dd.anc").removeClass("active");
                $("dd.anc").removeClass("anc");
            }
            if ($(e).attr("sort-type") == null) {
                $(e).attr("sort-type", 1);
                $(e).children().html("▲");
            } else if ($(e).attr("sort-type") == 1) {
                $(e).attr("sort-type", -1);
                $(e).children().html("▼");
            } else {
                $(e).attr("sort-type", 1);
                $(e).children().html("▲");
            }
            $(e).parent().addClass("active");
            $(e).parent().addClass("anc");
            Torrent1.model.listTorrent.sortcolonne = $(e).attr("sort-colonne");
            Torrent1.model.listTorrent.sorttype = $(e).attr("sort-type");
            this.triFusion();
            this.affiche();
        },

        next: function (id) {
            Torrent1.model.listTorrent.cpt = id;
            this.affiche();
        },
        resetSelectionne: function () {
            Torrent1.model.listTorrent.cpt = 0;
            Torrent1.model.listTorrent.selectionne = [];
        },
        recheck: function () {
            var liste = Torrent1.model.listTorrent.selectionne;
            var listeo = Torrent1.model.listTorrent.original;
            var listafaire = [];
            if (liste.length > 0) {
                $.each(liste, function (k, v) {
                    if (Torrent1.controller.listTorrent.torrentPeutEffectuerCommande(listeo[v], "recheck")) {
                        listafaire.push(v);
                    }
                });
            }

            $.ajax({
                url: Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'torrent/recheck/' + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion + ".json",
                dataType: "json",
                type: "POST",
                data: {hash: listafaire},
                //contentType: "application/json",
                success: function (response, textStatus, jqXHR) {
                    if (response.showdebugger == "ok") {

                    } else {

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Base.view.noty.generate("error", textStatus + " " + jqXHR + " " + errorThrown);

                }
            });
        },
        stop: function () {
            var liste = Torrent1.model.listTorrent.selectionne;
            var listeo = Torrent1.model.listTorrent.original;
            var listafaire = [];
            if (liste.length > 0) {
                $.each(liste, function (k, v) {
                    if (Torrent1.controller.listTorrent.torrentPeutEffectuerCommande(listeo[v], "stop")) {
                        listafaire.push(v);
                    }
                });
            }

            $.ajax({
                url: Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'torrent/stop/' + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion + ".json",
                dataType: "json",
                type: "POST",
                data: {hash: listafaire},
                //contentType: "application/json",
                success: function (response, textStatus, jqXHR) {
                    if (response.showdebugger == "ok") {

                    } else {

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Base.view.noty.generate("error", textStatus + " " + jqXHR + " " + errorThrown);

                }
            });
        },
        start: function () {
            var liste = Torrent1.model.listTorrent.selectionne;
            var listeo = Torrent1.model.listTorrent.original;
            var listafaire = [];
            if (liste.length > 0) {
                $.each(liste, function (k, v) {
                    if (Torrent1.controller.listTorrent.torrentPeutEffectuerCommande(listeo[v], "start")) {
                        listafaire.push(v);
                    }
                });
            }
            $.ajax({
                url: Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'torrent/start/' + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion + ".json",
                dataType: "json",
                type: "POST",
                data: {hash: listafaire},
                //contentType: "application/json",
                success: function (response, textStatus, jqXHR) {
                    if (response.showdebugger == "ok") {

                    } else {

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Base.view.noty.generate("error", textStatus + " " + jqXHR + " " + errorThrown);

                }
            });
        },
        pause: function () {
            var liste = Torrent1.model.listTorrent.selectionne;
            var listeo = Torrent1.model.listTorrent.original;
            var listafaire = [];
            if (liste.length > 0) {
                $.each(liste, function (k, v) {
                    if (Torrent1.controller.listTorrent.torrentPeutEffectuerCommande(listeo[v], "pause")) {
                        listafaire.push(v);
                    }
                });
            }
            $.ajax({
                url: Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'torrent/pause/' + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion + ".json",
                dataType: "json",
                type: "POST",
                data: {hash: listafaire},
                //contentType: "application/json",
                success: function (response, textStatus, jqXHR) {
                    if (response.showdebugger == "ok") {

                    } else {

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Base.view.noty.generate("error", textStatus + " " + jqXHR + " " + errorThrown);

                }
            });
        },
        delete: function () {
            var liste = Torrent1.model.listTorrent.selectionne;
            var listafaire = [];
            if (liste.length > 0) {
                $.each(liste, function (k, v) {
                    listafaire.push(v);
                });
            }
            Torrent1.model.listTorrent.selectionne = [];
            $.ajax({
                url: Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'torrent/delete/' + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion + ".json",
                dataType: "json",
                type: "POST",
                data: {hash: listafaire},
                //contentType: "application/json",
                success: function (response, textStatus, jqXHR) {
                    if (response.showdebugger == "ok") {

                    } else {

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Base.view.noty.generate("error", textStatus + " " + jqXHR + " " + errorThrown);
                }
            });
        },
        deleteAll: function () {
            var liste = Torrent1.model.listTorrent.selectionne;
            var listeo = Torrent1.model.listTorrent.original;
            var listafaire = [];
            var res = "Être vous sur de vouloir supprimer :<br>";

            if (liste.length > 0) {
                $.each(liste, function (k, v) {
                    listafaire.push(v);
                    res += listeo[v][1] + "<br>";
                });
            }
            Base.view.noty.generateConfirm(res, function () {
                    Torrent1.model.listTorrent.selectionne = [];
                    $.ajax({
                        url: Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'torrent/deleteall/' + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion + ".json",
                        dataType: "json",
                        type: "POST",
                        data: {hash: listafaire},
                        //contentType: "application/json",
                        success: function (response, textStatus, jqXHR) {
                            if (response.showdebugger == "ok") {
                                var torrent = response.torrent;
                                $.each(torrent, function (k, v) {
                                    if (v) {
                                        Base.view.noty.generate("success", '"' + k + '" a bien été supprimé');
                                    } else {
                                        Base.view.noty.generate("error", '"' + k + '"' + "n'a pas été supprimé");
                                    }
                                });
                            } else {

                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            Base.view.noty.generate("error", textStatus + " " + jqXHR + " " + errorThrown);

                        }
                    });
                }
            );

        },
        torrentPeutEffectuerCommande: function (torrent, commande) {
            var ret = true;
            var status = torrent[0];
            var dStatus = Torrent1.model.listTorrent.dStatus;
            switch (commande) {
                case "start" :
                {
                    ret = (!(status & dStatus.started) || (status & dStatus.paused) && !(status & dStatus.checking) && !(status & dStatus.hashing));
                    break;
                }
                case "pause" :
                {
                    ret = ((status & dStatus.started) && !(status & dStatus.paused) && !(status & dStatus.checking) && !(status & dStatus.hashing));
                    break;
                }
                case "unpause" :
                {
                    ret = ((status & dStatus.paused) && !(status & dStatus.checking) && !(status & dStatus.hashing));
                    break;
                }
                case "stop" :
                {
                    ret = ((status & dStatus.started) || (status & dStatus.hashing) || (status & dStatus.checking));
                    break;
                }
                case "recheck" :
                {
                    ret = !(status & dStatus.checking) && !(status & dStatus.hashing);
                    break;
                }
            }

            return(ret);
        }

    },
    detailsTorrent: {
        init: function () {
            Torrent1.view.detailsTorrent.init();
        },
        conversion: function (liste, force) {
            if (liste != null) {
                if (Torrent1.model.detailsTorrent.original.length == 0 || Torrent1.model.seedbox.changed || force) {
                    Torrent1.model.detailsTorrent.original = liste;
                } else {
                    $.each(liste, function (k, v) {
                        if (v == false) {
                            delete Torrent1.model.detailsTorrent.original[k];
                        } else {

                            Torrent1.model.detailsTorrent.original[k] = v;

                        }
                    });
                }

                Torrent1.model.detailsTorrent.liste = [];
                $.each(Torrent1.model.detailsTorrent.original, function (k, v) {
                    Torrent1.model.detailsTorrent.liste[Torrent1.model.detailsTorrent.liste.length] = v;
                });
            }
        },
        reset: function () {
            Torrent1.model.detailsTorrent.liste = [];
            Torrent1.model.detailsTorrent.original = [];
        }
    },
    filesTorrent: {
        init: function () {
            Torrent1.view.filesTorrent.init();
        },
        resetSelectionne: function () {
            Torrent1.model.filesTorrent.selectionne = [];
            Torrent1.model.filesTorrent.selectionneno = [];
        },
        reset: function () {
            this.resetSelectionne();
            $("#torrentdetailsfiles").empty();
            Torrent1.model.filesTorrent.changed = false;
            Torrent1.model.filesTorrent.original = [];
            Torrent1.model.filesTorrent.liste = [];
            Torrent1.model.filesTorrent.hauteurArbre = 0;
        },
        download: function (k) {
            var url = Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'torrent/download/' + Torrent1.model.listTorrent.selectionne[0] + "/" + Torrent1.model.filesTorrent.original[k][0] + "/" + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion;
            if (Torrent1.model.filesTorrent.original[k][2] == Torrent1.model.filesTorrent.original[k][3]) {
                $("#getdata").attr("action", url).submit();
            } else {
                var text = "Le fichier \"" + Base.model.path.basename(Torrent1.model.filesTorrent.original[k][1]) + "\" n'est pas complet<br>Voulez vous continuez le téléchargement ?";
                Base.view.noty.generateConfirm(text, function () {
                    $("#getdata").attr("action", url).submit();
                });
            }
            //window.open(url,"_blank", null);
        },
        priorite: function (priorite) {
            var liste = Torrent1.model.filesTorrent.selectionneno;
            var listafaire = [];
            if (liste.length > 0) {
                $.each(liste, function (k, v) {
                    listafaire.push(v);
                });
            }
            console.log(listafaire.join());
            this.resetSelectionne();
            $.ajax({
                url: Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'torrent/setPrioriteFile/' + Torrent1.model.listTorrent.selectionne[0] + "/" + priorite + "/" + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion + ".json",
                dataType: "json",
                type: "POST",
                data: {nofiles: listafaire},
                //contentType: "application/json",
                success: function (response, textStatus, jqXHR) {
                    if (response.showdebugger == "ok") {

                    } else {

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    Base.view.noty.generate("error", textStatus + " " + jqXHR + " " + errorThrown);
                }
            });
        },
        streaming: function (k) {
            //var url = Torrent.model.downloadFileTorrent(Torrent.model.listeselectionnee[0],Torrent.model.fileselectionnee[0]);
            if (Torrent1.model.filesTorrent.original[k][2] == Torrent1.model.filesTorrent.original[k][3]) {
                window.open(Base.controller.makeUrlBase() + 'torrent/streaming/' + Base.model.converter.paramUrl(Torrent1.model.baseUrl) + "/" + Torrent1.model.listTorrent.selectionne[0] + "/" + k + "/" + Base.model.path.basename(Torrent1.model.filesTorrent.original[k][1]) + ".html", "_blank", "menubar=no, status=no, scrollbars=no, toolbar=no,location=no,resizable=no, width=650, height=510");
            } else {
                var text = "Le fichier \"" + Base.model.path.basename(Torrent1.model.filesTorrent.original[k][1]) + "\" n'est pas complet<br>Voulez vous continuez le streaming ?";
                Base.view.noty.generateConfirm(text, function () {
                    window.open(Base.controller.makeUrlBase() + 'torrent/streaming/' + Base.model.converter.paramUrl(Torrent1.model.baseUrl) + "/" + Torrent1.model.listTorrent.selectionne[0] + "/" + k + "/" + Base.model.path.basename(Torrent1.model.filesTorrent.original[k][1]) + ".html", "_blank", "menubar=no, status=no, scrollbars=no, toolbar=no,location=no,resizable=no, width=650, height=510");
                });
            }
            //Torrent.view.fileTorrentsStreaming(url);
        },
        conversion: function (liste, force) {
            if (liste != null) {
                if (Torrent1.model.filesTorrent.original.length == 0 || Torrent1.model.seedbox.changed || force) {
                    Torrent1.model.filesTorrent.original = liste;
                } else {
                    $.each(liste, function (k, v) {
                        if (v == false) {
                            delete Torrent1.model.filesTorrent.original[k];
                        } else {
                            if (Torrent1.model.filesTorrent.original[k]) {
                                $.each(v, function (kk, vv) {
                                    Torrent1.model.filesTorrent.original[k][kk] = vv;
                                });
                            } else {
                                Torrent1.model.filesTorrent.original[k] = v;
                            }
                        }
                    });
                }

                Torrent1.model.filesTorrent.liste = [];
                var dossier = [];
                for (var j = 0; j < Torrent1.model.filesTorrent.original.length; j++) {
                    var v = Torrent1.model.filesTorrent.original[j];
                    //Torrent1.model.filesTorrent.liste[Torrent1.model.filesTorrent.liste.length]= v;
                    var paths = v[1].split("/");
                    var dire = "/";
                    var ancdire = "/";
                    for (var i = 0; i < paths.length; i++) {
                        var parent = 0;
                        if (i == paths.length - 1) {
                            //File
                            if (i == 0) {
                                if (!Torrent1.model.filesTorrent.liste[0])
                                    Torrent1.model.filesTorrent.liste[0] = {dossier: [], file: []};
                                Torrent1.model.filesTorrent.liste[0].file[Torrent1.model.filesTorrent.liste[0].file.length] = [paths[i], v[0], v[2], v[3], v[4], v[5]];
                            } else {
                                if (!Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id])
                                    Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id] = {dossier: [], file: [], back: dossier[(ancdire + paths[i - 1])].parent};
                                Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id].file[Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id].file.length] = [paths[i], v[0], v[2], v[3], v[4], v[5]];
                            }
                        } else {
                            //Dossier
                            var where

                            if (i == 0) {
                                parent = 0;
                                if (!Torrent1.model.filesTorrent.liste[parent])
                                    Torrent1.model.filesTorrent.liste[parent] = {dossier: [], file: []};
                                where = Torrent1.model.filesTorrent.liste[parent].dossier.length;
                                if (!dossier[(dire + paths[i])]) {
//                                Torrent1.model.filesTorrent.liste[parent].dossier[where] = {nom :paths[i], parent:parent,childs:Torrent1.model.filesTorrent.liste.length,chunkscomplete : v[2],chunkstotal :v[3], size: v[4]};
                                    Torrent1.model.filesTorrent.liste[parent].dossier[where] = [paths[i], Torrent1.model.filesTorrent.liste.length, Base.model.converter.iv(v[2]), Base.model.converter.iv(v[3]), Base.model.converter.iv(v[4]), Base.model.converter.iv(v[5]), [v[0]]];
                                } else {
                                    where = dossier[(dire + paths[i])].ou;
                                    Torrent1.model.filesTorrent.liste[parent].dossier[where][2] += Base.model.converter.iv(v[2]);
                                    Torrent1.model.filesTorrent.liste[parent].dossier[where][3] += Base.model.converter.iv(v[3]);
                                    Torrent1.model.filesTorrent.liste[parent].dossier[where][4] += Base.model.converter.iv(v[4]);
                                    Torrent1.model.filesTorrent.liste[parent].dossier[where][5] = (Torrent1.model.filesTorrent.liste[parent].dossier[where][5] == Base.model.converter.iv(v[5]) ? Base.model.converter.iv(v[5]) : -1);
                                    Torrent1.model.filesTorrent.liste[parent].dossier[where][6][Torrent1.model.filesTorrent.liste[parent].dossier[where][6].length] = v[0];
                                }
                            } else {
                                //parent = dossier[(ancdire+paths[i-1])].parent;
                                if (!Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id])
                                    Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id] = {dossier: [], file: [], back: dossier[(ancdire + paths[i - 1])].parent};
                                where = Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id].dossier.length;

                                if (!dossier[(dire + paths[i])]) {
                                    Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id].dossier[where] = [paths[i], Torrent1.model.filesTorrent.liste.length, Base.model.converter.iv(v[2]), Base.model.converter.iv(v[3]), Base.model.converter.iv(v[4]), Base.model.converter.iv(v[5]), [v[0]]];
                                } else {
                                    where = dossier[(dire + paths[i])].ou;
                                    // console.log((dire+paths[i]));
                                    // console.log(where);
                                    //  console.log(Torrent1.model.filesTorrent.liste[dossier[(ancdire+paths[i-1])].id].dossier[where]);
                                    Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id].dossier[where][2] += Base.model.converter.iv(v[2]);
                                    Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id].dossier[where][3] += Base.model.converter.iv(v[3]);
                                    Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id].dossier[where][4] += Base.model.converter.iv(v[4]);
                                    Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id].dossier[where][5] = (Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id].dossier[where][5] == Base.model.converter.iv(v[5]) ? Base.model.converter.iv(v[5]) : -1);
                                    Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id].dossier[where][6][Torrent1.model.filesTorrent.liste[dossier[(ancdire + paths[i - 1])].id].dossier[where][6].length] = v[0];
                                }
                            }
                            if (!dossier[(dire + paths[i])])
                                dossier[(dire + paths[i])] = {id: Torrent1.model.filesTorrent.liste.length, ou: where, parent: (i < 1 ? 0 : dossier[(ancdire + paths[i - 1])].id)};
                        }
                        if (i > 0) {
                            ancdire += "/" + paths[i - 1];
                        }
                        dire += "/" + paths[i];


                    }
                    //Torrent1.model.filesTorrent.liste[ Torrent1.model.filesTorrent.liste.length-1] = vv;


                }
                //Tri par fusion si nécessaire
                /*if (Torrent.model.sortcolonne > -1){
                 Torrent.model.fileliste = Base.model.tableau.triFusion(Torrent.model.fileliste,Torrent.model.sortcolonne,Torrent.model.sorttype);
                 }*/


            }
        }
    },
    trackersTorrent: {
        init: function () {
            Torrent1.view.trackersTorrent.init();
        },
        conversion: function (liste, force) {
            Torrent1.model.trackersTorrent.liste = liste;
        }
    },
    addTorrent: {
        init: function () {
            Torrent1.view.addTorrent.init();
        },
        show: function () {
            Torrent1.view.addTorrent.show()
        },
        hide: function () {
            Torrent1.view.addTorrent.hide();
        },
        upload: function (e) {
            e.preventDefault();
            var formData = new FormData($("#addtorrent")[0]);
            var nbtorrent = $('input[name="nbtorrents"]').val();
            if (nbtorrent) {
                for (id = 0; id < nbtorrent; id++) {
                    if ($('.torrent' + id + 'ajoutecheck').is(":checked")) {
                        formData.append("torrent" + id + "addbibli", "on");
                    }
                }

            }
            $.ajax({
                url: Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'torrent/send/' + Torrent1.model.nomseedbox + "/" + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion + ".json",
                async: false,
                //dataType :"json",
                type: "post",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function (response, textStatus, jqXHR) {
                    //afficheResultat(container,response);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    // afficheErreur(jqXHR.responseText,container);
                }

            });
        },
        files: {
            check: function (check) {
                if (check) {
                    var formData = new FormData($("#addtorrent")[0]);
                    $.ajax({
                        url: Base.controller.makeUrlBase() + "torrent/infofichier.json",
                        async: false,
                        //dataType :"json",
                        type: "post",
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: formData,
                        success: function (response, textStatus, jqXHR) {
                            //afficheResultat(container,response);
                            Torrent1.view.addTorrent.files.show(response.torrent);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            // afficheErreur(jqXHR.responseText,container);
                        }

                    });
                } else {
                    $('#addTorrentDetails').empty();
                }
            },
            file: {
                movie: {
                    recherche: function (id) {
                        var recherche = $("#torrent" + id + "suggestrecherche").val();
                        var url = Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'film/recherche/' + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion;

                        $.ajax({
                            url: url + ".json",
                            dataType: "json",
                            type: "POST",
                            data: {recherche: recherche},

                            //contentType: "application/json",
                            success: function (response, textStatus, jqXHR) {
                                //console.log(response);
                                Torrent1.view.addTorrent.files.file.movie.recherche.results(id, response);
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                Base.view.noty.generate("error", "Impossible de se connecter à " + Torrent1.model.nomseedbox);
                            }
                        });
                    },
                    allrecherche: function (id, code, type) {
                        var url = Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'film/getInfosFilm/' + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion + "/" + code;
                        if (type)
                            url += "/all"
                        $.ajax({
                            url: url + ".json",
                            dataType: "json",
                            type: "GET",

                            //contentType: "application/json",
                            success: function (response, textStatus, jqXHR) {
                                //console.log(response);
                                Torrent1.view.addTorrent.files.file.movie.recherche.allrecherche(id, response.film);
                                //Torrent1.view.addTorrent.files.file.movie.recherche.results(id,response.film);
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                Base.view.noty.generate("error", "Impossible de se connecter à " + Torrent1.model.nomseedbox);
                            }
                        });
                    }

                }
            }
        }
    },
    createTorrent: {
        init: function () {
            Torrent1.view.createTorrent.init();
            Torrent1.model.createTorrent.folder.loader = Base.view.loader.make("folder");
            //Torrent1.model.createTorrent.backup = $("#createTorrentContenu").html();
        },
        show: function () {
            //$("#createTorrentContenu").html(Torrent1.model.createTorrent.backup);
            Torrent1.view.createTorrent.show();
            Torrent1.controller.createTorrent.folder.loader();
        },
        hide: function () {
            Torrent1.view.createTorrent.hide();
        },
        folder: {
            conversion: function (liste) {
                if (liste != null) {

                    //Torrent1.model.filesTorrent.liste = [];

                    var dossier = [];
                    for (var j = 0; j < liste.length; j++) {
                        var v = liste[j];
                        //Torrent1.model.filesTorrent.liste[Torrent1.model.filesTorrent.liste.length]= v;
                        var paths = v[0].split("/");
                        var dire = "/";
                        var ancdire = "/";
                        for (var i = 0; i < paths.length; i++) {
                            var parent = 0;
                            if (paths[0] !== '') {
                                if (i == paths.length - 1 && v[2] === "f") {
                                //File
                                if (i == 0) {
                                    if (!Torrent1.model.createTorrent.folder.liste[0])
                                        Torrent1.model.createTorrent.folder.liste[0] = {dossier: [], file: [], path: dire};
                                    Torrent1.model.createTorrent.folder.liste[0].file[Torrent1.model.createTorrent.folder.liste[0].file.length] = [paths[i], v[0], v[1], v[2]];
                                } else {
                                    if (!Torrent1.model.createTorrent.folder.liste[dossier[(ancdire + paths[i - 1])].id])
                                        Torrent1.model.createTorrent.folder.liste[dossier[(ancdire + paths[i - 1])].id] = {dossier: [], file: [], back: dossier[(ancdire + paths[i - 1])].parent, path: dire};
                                    Torrent1.model.createTorrent.folder.liste[dossier[(ancdire + paths[i - 1])].id].file[Torrent1.model.createTorrent.folder.liste[dossier[(ancdire + paths[i - 1])].id].file.length] = [paths[i], v[0], v[1], v[2]];
                                }
                            } else {
                                //Dossier
                                    if (i == paths.length - 1) {
                                        console.info(dire);
                                        console.info(v[0]);
                                    }
                                    var where

                                if (i == 0) {
                                    parent = 0;
                                    if (!Torrent1.model.createTorrent.folder.liste[parent])
                                        Torrent1.model.createTorrent.folder.liste[parent] = {dossier: [], file: [], path: dire};
                                    where = Torrent1.model.createTorrent.folder.liste[parent].dossier.length;
                                    if (!dossier[(dire + paths[i])]) {
//                                Torrent1.model.createTorrent.folder.liste[parent].dossier[where] = {nom :paths[i], parent:parent,childs:Torrent1.model.createTorrent.folder.liste.length,chunkscomplete : v[2],chunkstotal :v[3], size: v[4]};
                                        Torrent1.model.createTorrent.folder.liste[parent].dossier[where] = [paths[i], Torrent1.model.createTorrent.folder.liste.length, Base.model.converter.iv((v[2] === 'f' ? v[1] : 0)), [v[0]]];
                                    } else {
                                        where = dossier[(dire + paths[i])].ou;
                                        Torrent1.model.createTorrent.folder.liste[parent].dossier[where][2] += Base.model.converter.iv((v[2] === 'f' ? v[1] : 0));
                                        Torrent1.model.createTorrent.folder.liste[parent].dossier[where][3][Torrent1.model.createTorrent.folder.liste[parent].dossier[where][3].length] = v[0];
                                    }
                                } else {
                                    //parent = dossier[(ancdire+paths[i-1])].parent;
                                    if (!Torrent1.model.createTorrent.folder.liste[dossier[(ancdire + paths[i - 1])].id])
                                        Torrent1.model.createTorrent.folder.liste[dossier[(ancdire + paths[i - 1])].id] = {dossier: [], file: [], back: dossier[(ancdire + paths[i - 1])].parent, path: dire};
                                    where = Torrent1.model.createTorrent.folder.liste[dossier[(ancdire + paths[i - 1])].id].dossier.length;

                                    if (!dossier[(dire + paths[i])]) {
                                        Torrent1.model.createTorrent.folder.liste[dossier[(ancdire + paths[i - 1])].id].dossier[where] = [paths[i], Torrent1.model.createTorrent.folder.liste.length, Base.model.converter.iv((v[2] === 'f' ? v[1] : 0)), [v[0]]];
                                    } else {
                                        where = dossier[(dire + paths[i])].ou;
                                        // console.log((dire+paths[i]));
                                        // console.log(where);
                                        //  console.log(Torrent1.model.createTorrent.folder.liste[dossier[(ancdire+paths[i-1])].id].dossier[where]);
                                        Torrent1.model.createTorrent.folder.liste[dossier[(ancdire + paths[i - 1])].id].dossier[where][2] += Base.model.converter.iv((v[2] === 'f' ? v[1] : 0));
                                        Torrent1.model.createTorrent.folder.liste[dossier[(ancdire + paths[i - 1])].id].dossier[where][3][Torrent1.model.createTorrent.folder.liste[dossier[(ancdire + paths[i - 1])].id].dossier[where][3].length] = v[0];
                                    }
                                }
                                if (!dossier[(dire + paths[i])])
                                    dossier[(dire + paths[i])] = {id: Torrent1.model.createTorrent.folder.liste.length, ou: where, parent: (i < 1 ? 0 : dossier[(ancdire + paths[i - 1])].id)};
                            }
                            if (i > 0) {
                                ancdire += paths[i - 1] + "/";
                            }
                                dire += paths[i] + "/";
                            }

                        }
                        //Torrent1.model.filesTorrent.liste[ Torrent1.model.filesTorrent.liste.length-1] = vv;


                    }
                    //Tri par fusion si nécessaire
                    /*if (Torrent.model.sortcolonne > -1){
                     Torrent.model.fileliste = Base.model.tableau.triFusion(Torrent.model.fileliste,Torrent.model.sortcolonne,Torrent.model.sorttype);
                     }*/


                }
            },
            loader: function () {
                Torrent1.controller.createTorrent.folder.showLoader();
                var url = Base.controller.makeUrlBase(Torrent1.model.baseUrl) + 'repertoire/liste/' + Base.model.utilisateur.login + "/" + Base.model.utilisateur.keyconnexion;
                $.ajax({
                    url: url + ".json",
                    dataType: "json",
                    //contentType: "application/json",
                    success: function (response, textStatus, jqXHR) {
                        Torrent1.controller.createTorrent.folder.hideLoader();
                        Torrent1.controller.createTorrent.folder.conversion(response.rep);
                        Torrent1.view.createTorrent.afficheArbre();
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        Base.view.noty.generate("error", "Impossible de récupéré le répertoire de " + Torrent1.model.nomseedbox);
                    }
                });
            },
            showLoader: function () {
                Torrent1.model.createTorrent.folder.loader.show();
            },
            hideLoader: function () {
                Torrent1.model.createTorrent.folder.loader.hide();
            }
        }
    },
    loader: {
        init: function () {
            Torrent1.view.loaders.init();
        }
    }

};