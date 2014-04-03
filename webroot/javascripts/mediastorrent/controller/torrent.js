/**
 * Created by salorium on 15/03/14.
 */
Torrent.controller =  {
    init : function (seedbox) {
        hauteur = Base.model.conf.containerHeight() - Base.model.html.hauteur(".container nav");
        Torrent.view.fixedHeightContainer(hauteur);
        //console.log($("#moitiegauche").css("padding-top"));
        Torrent.model.hauteurTorrent =$("#moitiegauche").height()-Base.model.html.hauteur("#moitiegauche dl");
        Torrent.model.baseUrl = seedbox[0].hostname;
        Torrent.model.nomseedbox = seedbox[0].nom;
        Torrent.model.seedboxs = seedbox;
        $("#recherchesubmit").attr("onclick","Torrent.controller.rechercheTorrent();");
        /*var input = $("#recherche")[0];
        input.onupdate = input.onkeyup = function() {
            if ($.trim(input.value).length > 1){
                Torrent.controller.rechercheTorrent();
            }else{
                Torrent.model.listerecherche = [];
            }
        }*/
        Base.view.fixedHeight("#addTorrentContenu",$("#addTorrent").height()-Base.model.html.hauteur("#addTorrentTitle"));
        Base.view.fixedHeight("#addTorrentDetails",$("#addTorrentContenu").height()-$("#baseaddTorrent").height()-$("#divbouttonaddtorrent").height());
        Base.view.fixedHeight("#panel2-1",$("#moitiedroite").height()-Base.model.html.hauteur("#moitiedroite > dl"));
        Base.view.fixedHeight("#panel2-2",$("#moitiedroite").height()-Base.model.html.hauteur("#moitiedroite > dl"));
        Base.view.fixedHeight("#panel2-3",$("#moitiedroite").height()-Base.model.html.hauteur("#moitiedroite > dl"));
        Base.view.fixedHeight("#panel2-4",$("#moitiedroite").height()-Base.model.html.hauteur("#moitiedroite > dl"));
        //Base.view.fixedHeight("#panel2-2 > table",$("#panel2-2").height());
        console.log(Base.model.html.hauteur("#panel2-2 > table > thead"));
        Base.view.fixedHeight("#torrentdetailsfiles",$("#panel2-2 > table").height()-Base.model.html.hauteur("#panel2-2 > thead"));
        Torrent.view.initSeedbox(0);
        Torrent.model.container.listtorrent = $("#listorrent");
        Torrent.model.container.listtorrent.empty();
        Torrent.model.container.listtorrent.append("<span class='bt' onclick='Torrent.controller.next(0)'>▲</span>");
        Torrent.model.container.listtorrent.append(Torrent.view.listeTorrent());
        Torrent.model.nbtorrents =Math.floor(((Torrent.model.hauteurTorrent - Base.model.html.hauteur("span.bt")*2)/Base.model.html.hauteur("fieldset.torrent")));
        Torrent.model.container.listtorrent.empty();
        Torrent.controller.update("");
        Torrent.controller.addTorrent.addTorrentHide();

    },
    rechercheTorrent:function(){
        var recher = new RegExp("("+$("#recherche").val()+")","gi");
        Torrent.model.listerecherche = [];
        var liste = Torrent.model.liste.clone();

        console.log(liste);
        $.each ( liste, function(k,v){
            if ( recher.test(v[1])){
                v[1] = v[1].replace(recher,'<span class="success radius label">$1</span>');
                Torrent.model.listerecherche.push(v);
            }
        });
    },
    recheckTorrent:function(){
        var liste = Torrent.model.listeselectionnee;
        var listeo = Torrent.model.listeoriginal;
        var listafaire = [];
        if (liste.length >0){
            $.each(liste, function(k,v){
                if ( Torrent.controller.torrentPeutEffectuerCommande(listeo[v],"recheck")){
                    listafaire.push(v);
                }
            });
        }

        $.ajax({
            url: "http://"+Torrent.model.baseUrl+'/torrent/recheck/'+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion+".json",
            dataType: "json",
            type: "POST",
            data: {hash:listafaire},
            //contentType: "application/json",
            success: function(response, textStatus, jqXHR){
                if (response.showdebugger == "ok"){

                }else{

                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                Base.view.noty.generate("error",textStatus+" "+jqXHR+" "+errorThrown);

            }
        });
    },
    stopTorrent: function (){
        var liste = Torrent.model.listeselectionnee;
        var listeo = Torrent.model.listeoriginal;
        var listafaire = [];
        if (liste.length >0){
            $.each(liste, function(k,v){
                if ( Torrent.controller.torrentPeutEffectuerCommande(listeo[v],"stop")){
                    listafaire.push(v);
                }
            });
        }

        $.ajax({
            url: "http://"+Torrent.model.baseUrl+'/torrent/stop/'+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion+".json",
            dataType: "json",
            type: "POST",
            data: {hash:listafaire},
            //contentType: "application/json",
            success: function(response, textStatus, jqXHR){
                if (response.showdebugger == "ok"){

                }else{

                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                Base.view.noty.generate("error",textStatus+" "+jqXHR+" "+errorThrown);

            }
        });
    },
    startTorrent: function (){
        var liste = Torrent.model.listeselectionnee;
        var listeo = Torrent.model.listeoriginal;
        var listafaire = [];
        if (liste.length >0){
            $.each(liste, function(k,v){
                if ( Torrent.controller.torrentPeutEffectuerCommande(listeo[v],"start")){
                    listafaire.push(v);
                }
            });
        }

        $.ajax({
            url: "http://"+Torrent.model.baseUrl+'/torrent/start/'+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion+".json",
            dataType: "json",
            type: "POST",
            data: {hash:listafaire},
            //contentType: "application/json",
            success: function(response, textStatus, jqXHR){
                if (response.showdebugger == "ok"){

                }else{

                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                Base.view.noty.generate("error",textStatus+" "+jqXHR+" "+errorThrown);

            }
        });
    },
    pauseTorrent: function (){
        var liste = Torrent.model.listeselectionnee;
        var listeo = Torrent.model.listeoriginal;
        var listafaire = [];
        if (liste.length >0){
            $.each(liste, function(k,v){
                if ( Torrent.controller.torrentPeutEffectuerCommande(listeo[v],"pause")){
                    listafaire.push(v);
                }
            });
        }

        $.ajax({
            url: "http://"+Torrent.model.baseUrl+'/torrent/pause/'+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion+".json",
            dataType: "json",
            type: "POST",
            data: {hash:listafaire},
            //contentType: "application/json",
            success: function(response, textStatus, jqXHR){
                if (response.showdebugger == "ok"){

                }else{

                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                Base.view.noty.generate("error",textStatus+" "+jqXHR+" "+errorThrown);

            }
        });
    },
    deleteTorrent: function (){
        var liste = Torrent.model.listeselectionnee;
        var listafaire = [];
        if (liste.length >0){
            $.each(liste, function(k,v){
                listafaire.push(v);
            });
        }
        Torrent.model.listeselectionnee = [];
        $.ajax({
            url: "http://"+Torrent.model.baseUrl+'/torrent/delete/'+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion+".json",
            dataType: "json",
            type: "POST",
            data: {hash:listafaire},
            //contentType: "application/json",
            success: function(response, textStatus, jqXHR){
                if (response.showdebugger == "ok"){

                }else{

                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                Base.view.noty.generate("error",textStatus+" "+jqXHR+" "+errorThrown);
            }
        });
    },
    deleteAllTorrent: function (){
        var liste = Torrent.model.listeselectionnee;
        var listeo = Torrent.model.listeoriginal;
        var listafaire = [];
        var res = "Être vous sur de vouloir supprimer :<br>";

        if (liste.length >0){
            $.each(liste, function(k,v){
                listafaire.push(v);
                res += listeo[v][1]+"<br>";
            });
        }
        Base.view.noty.generateConfirm(res,function(){
                Torrent.model.listeselectionnee = [];
                $.ajax({
                    url: "http://"+Torrent.model.baseUrl+'/torrent/deleteall/'+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion+".json",
                    dataType: "json",
                    type: "POST",
                    data: {hash:listafaire},
                    //contentType: "application/json",
                    success: function(response, textStatus, jqXHR){
                        if (response.showdebugger == "ok"){
                            var torrent = response.torrent;
                            $.each(torrent, function(k,v){
                                if (v){
                                    Base.view.noty.generate("success",'"'+k+'" a bien été supprimé');
                                }else{
                                    Base.view.noty.generate("error",'"'+k+'"'+"n'a pas été supprimé");
                                }
                            });
                        }else{

                        }
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        Base.view.noty.generate("error",textStatus+" "+jqXHR+" "+errorThrown);

                    }
                });
            }
        );

    },

    reloadSeedbox: function(id){
        Torrent.view.initSeedbox(id);
        Torrent.model.changedurl = true;
        Torrent.model.listeselectionnee =[];
        Torrent.model.torrentselectionneedetail = null;
        Torrent.model.detaillisteoriginal = [];
        Torrent.model.detailliste = [];
        Torrent.model.filelisteoriginal = [];
        Torrent.model.fileliste = [];
    },
    tri : function(e){
        if ($("dd.anc").length > 0 && $("dd.anc").children().attr("sort-colonne") != $(e).attr("sort-colonne")){
            $("dd.anc").children().children().html("");
            $("dd.anc").children().removeAttr("sort-type");
            $("dd.anc").removeClass("active");
            $("dd.anc").removeClass("anc");
        }
        if ($(e).attr("sort-type") == null){
            $(e).attr("sort-type",1);
            $(e).children().html("▲");
        }else if ($(e).attr("sort-type")== 1){
            $(e).attr("sort-type",-1);
            $(e).children().html("▼");
        }else{
            $(e).attr("sort-type",1);
            $(e).children().html("▲");
        }
        $(e).parent().addClass("active");
        $(e).parent().addClass("anc");
        Torrent.model.sortcolonne = $(e).attr("sort-colonne");
        Torrent.model.sorttype = $(e).attr("sort-type");
        Torrent.controller.afficheTorrent();
    },
    afficheTorrent:function (){
        var listtorrent = Torrent.model.container.listtorrent;
        listtorrent.empty();
        var liste =  Torrent.model.liste;
        if ( Torrent.model.listerecherche.length > 0){
            liste = Torrent.model.listerecherche;
        }
        if (Torrent.model.cpt > liste.length )
            Torrent.model.cpt = 0;
        if (Torrent.model.cpt > 0){
            Torrent.model.container.listtorrent.append("<span class='bt' onclick='Torrent.controller.next("+(Torrent.model.cpt-1)+")'>▲</span>");
        }
        var max = Torrent.model.cpt + Torrent.model.nbtorrents;
        if (Torrent.model.cpt+Torrent.model.nbtorrents > liste.length)
            max = liste.length;
        for (i=Torrent.model.cpt; i < max;i++){
            Torrent.model.container.listtorrent.append(Torrent.view.listeTorrent(liste[i],i));
        }

        $('fieldset.torrent').bind('mousewheel DOMMouseScroll', function(e) {
            e.preventDefault();
            delta = e.originalEvent.detail;
            if (e.originalEvent.wheelDelta)
                delta = e.originalEvent.wheelDelta*-1;
            if (delta < 0){
                Torrent.model.cpt--;
                if (Torrent.model.cpt <0)
                    Torrent.model.cpt = 0;
            }else{
                Torrent.model.cpt++;

                if (Torrent.model.cpt+Torrent.model.nbtorrents > liste.length){
                    Torrent.model.cpt = liste.length-Torrent.model.nbtorrents;
                }
                if (Torrent.model.cpt <0)
                    Torrent.model.cpt = 0;
            }
            Torrent.controller.afficheTorrent();
        });
        $('fieldset.torrent').mousedown(function(e) {
            e.preventDefault();

            switch (e.which){
                case 1:
                    if (e.shiftKey) {
                        Torrent.model.listeselectionnee=[];
                        $(".torrent").removeClass("torrentselect");
                        if (Torrent.model.listeselectionneeid> -1){
                            max = Base.model.converter.iv($(e.currentTarget).attr("idcpt"));
                            i1 = Base.model.converter.iv(Torrent.model.listeselectionneeid);
                            if (i1 < max ){
                                for (i=i1;i< max;i++){

                                    Torrent.model.listeselectionnee.push(liste[i][27]);
                                    $("#"+liste[i][27]).addClass("torrentselect");
                                }
                            }else{
                                for (i=i1;i> max;i--){
                                    Torrent.model.listeselectionnee.push(liste[i][27]);
                                    $("#"+liste[i][27]).addClass("torrentselect");
                                }
                            }
                            Torrent.model.listeselectionnee.push($(e.currentTarget).attr("id"));
                        }else{
                            Torrent.model.listeselectionnee.push($(e.currentTarget).attr("id"));
                            Torrent.model.listeselectionneeid = ($(e.currentTarget).attr("idcpt"));

                        }
                        Torrent.model.detailliste = [];
                        Torrent.model.detaillisteoriginal = [];
                        Torrent.model.fileliste = [];
                        Torrent.model.filelisteoriginal = [];
                        Torrent.model.changeselecttorrent = true;
                        $(e.currentTarget).addClass("torrentselect");
                        Torrent.view.detailsTorrent();
                        Torrent.view.filesTorrent();
                        //  mon action
                    }else if (e.ctrlKey){
                        id = $.inArray($(e.currentTarget).attr("id"),Torrent.model.listeselectionnee)
                        if (id > -1){
                            $(e.currentTarget).removeClass("torrentselect");
                            Torrent.model.listeselectionnee.splice(id,1);
                        }else{
                            Torrent.model.listeselectionnee.push($(e.currentTarget).attr("id"));
                            $(e.currentTarget).addClass("torrentselect");
                        }
                        Torrent.model.detailliste = [];
                        Torrent.model.detaillisteoriginal = [];
                        Torrent.model.fileliste = [];
                        Torrent.model.filelisteoriginal = [];
                        Torrent.model.changeselecttorrent = true;
                        Torrent.model.listeselectionneeid = ($(e.currentTarget).attr("idcpt"));
                        Torrent.view.detailsTorrent();
                        Torrent.view.filesTorrent();
                    }else if (! (Torrent.model.listeselectionnee.length == 1 && Torrent.model.listeselectionnee[0]==$(e.currentTarget).attr("id") )){
                        Torrent.model.listeselectionnee=[];
                        Torrent.model.listeselectionnee.push($(e.currentTarget).attr("id"));
                        Torrent.controller.detailsTorrent();
                        Torrent.model.listeselectionneeid = ($(e.currentTarget).attr("idcpt"));
                        Torrent.model.detailliste = Torrent.model.listeoriginal[$(e.currentTarget).attr("id")];
                        Torrent.model.detaillisteoriginal = Torrent.model.listeoriginal[$(e.currentTarget).attr("id")];
                        Torrent.model.fileselectionnee=[];
                        Torrent.model.fileliste = [];
                        Torrent.model.filelisteoriginal = [];
                        Torrent.model.changeselecttorrent = true;
                        $(".torrent").removeClass("torrentselect");
                        $(e.currentTarget).addClass("torrentselect");
                        Torrent.view.detailsTorrent();
                        Torrent.view.filesTorrent();
                    }
                    break;
                case 3:
                    e.stopPropagation();
                    if (! (Torrent.model.listeselectionnee.length == 1 && Torrent.model.listeselectionnee[0]==$(e.currentTarget).attr("id") )){
                        Torrent.model.listeselectionnee=[];
                        Torrent.model.listeselectionnee.push($(e.currentTarget).attr("id"));
                        Torrent.model.listeselectionneeid = ($(e.currentTarget).attr("idcpt"));
                        Torrent.model.detailliste = Torrent.model.listeoriginal[$(e.currentTarget).attr("id")];
                        Torrent.model.detaillisteoriginal = Torrent.model.listeoriginal[$(e.currentTarget).attr("id")];
                        Torrent.model.fileselectionnee=[];
                        Torrent.model.changeselecttorrent = true;
                        $(".torrent").removeClass("torrentselect");
                        $(e.currentTarget).addClass("torrentselect");
                        Torrent.model.fileliste = [];
                        Torrent.model.filelisteoriginal = [];
                        Torrent.view.detailsTorrent();
                        Torrent.view.filesTorrent();
                    }
                    break;
            }

        });
        $('fieldset.torrent').dblclick(function(e){
            e.preventDefault();
            $("#btdetails").parent().children().removeClass('active');
            $("#btdetails").addClass('active');
            $("#panel2-1").parent().children().removeClass('active');
            $("#panel2-1").addClass('active');

        });
        if (liste.length > Torrent.model.nbtorrents){
            var max = Torrent.model.cpt+1;
            if (max+Torrent.model.nbtorrents-1 < liste.length)
            //max = this.liste.length-3;
                Torrent.model.container.listtorrent.append("<span class='bt' onclick='Torrent.controller.next("+max+")'>▼</span>");
        }
    },
    next:function (id){
        Torrent.model.cpt = id;
        Torrent.controller.afficheTorrent();
    },
    conversionListe : function (liste){
        if (liste != null){
            if (Torrent.model.listeoriginal.length == 0 || Torrent.model.changedurl){
                Torrent.model.listeoriginal = liste;
                Torrent.model.cpt = 0;
                Torrent.model.changedurl = false;
            }else{
                $.each(liste, function(k,v){
                    if (v == false){
                        delete Torrent.model.listeoriginal[k];
                    }else{
                        if(Torrent.model.listeoriginal[k]){
                            $.each(v, function (kk,vv){
                                Torrent.model.listeoriginal[k][kk]= vv;
                            });
                        }else{
                            Torrent.model.listeoriginal[k]= v;
                        }
                    }
                });
            }

            Torrent.model.liste = [];
            $.each(Torrent.model.listeoriginal, function(k,v){
                Torrent.model.liste[Torrent.model.liste.length]= v;
            });
            //Tri par fusion si nécessaire
            if (Torrent.model.sortcolonne > -1){
                Torrent.model.liste = Base.model.tableau.triFusion(Torrent.model.liste,Torrent.model.sortcolonne,Torrent.model.sorttype);
            }


        }
    },
    conversionListeFiles : function (liste,force){
        if (liste != null){
            if (Torrent.model.filelisteoriginal.length == 0 || Torrent.model.changedurl|| force ){
                Torrent.model.filelisteoriginal = liste;
            }else{
                $.each(liste, function(k,v){
                    if (v == false){
                        delete Torrent.model.filelisteoriginal[k];
                    }else{
                        if(Torrent.model.filelisteoriginal[k]){
                            $.each(v, function (kk,vv){
                                Torrent.model.filelisteoriginal[k][kk]= vv;
                            });
                        }else{
                            Torrent.model.filelisteoriginal[k]= v;
                        }
                    }
                });
            }

            Torrent.model.fileliste = [];
            $.each(Torrent.model.filelisteoriginal, function(k,v){
                Torrent.model.fileliste[Torrent.model.fileliste.length]= v;
            });
            //Tri par fusion si nécessaire
            /*if (Torrent.model.sortcolonne > -1){
             Torrent.model.fileliste = Base.model.tableau.triFusion(Torrent.model.fileliste,Torrent.model.sortcolonne,Torrent.model.sorttype);
             }*/


        }
    },
    conversionListeDetails : function (liste,force){
        if (liste != null){
            if (Torrent.model.detaillisteoriginal.length == 0 || Torrent.model.changedurl||force ){
                Torrent.model.detaillisteoriginal = liste;
            }else{
                $.each(liste, function(k,v){
                    if (v == false){
                        delete Torrent.model.detaillisteoriginal[k];
                    }else{

                        Torrent.model.detaillisteoriginal[k]= v;

                    }
                });
            }

            Torrent.model.detailliste = [];
            $.each(Torrent.model.detaillisteoriginal, function(k,v){
                Torrent.model.detailliste[Torrent.model.detailliste.length]= v;
            });
            //Tri par fusion si nécessaire
            /*if (Torrent.model.sortcolonne > -1){
             Torrent.model.fileliste = Base.model.tableau.triFusion(Torrent.model.fileliste,Torrent.model.sortcolonne,Torrent.model.sorttype);
             }*/


        }
    },
    update: function(cid){
        var url = "http://"+Torrent.model.baseUrl+'/torrent/liste/'+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion+"/"+cid;
        if ( Torrent.model.listeselectionnee.length == 1){
            url += "/"+Torrent.model.listeselectionnee[0];
        }
        $.ajax({
            url: url+".json",
            dataType: "json",
            //contentType: "application/json",
            success: function(response, textStatus, jqXHR){
                if ( response.host == Torrent.model.baseUrl){
                    if (response.showdebugger == "ok"){
                        var res = response.torrent;
                        torrent = res[0];
                        Torrent.view.statsTorrent(res[2],res[3],res[4]);
                        Torrent.controller.conversionListe(torrent);
                        if ($("#recherche").val().length > 1){
                            Torrent.controller.rechercheTorrent();
                        }
                        Torrent.controller.afficheTorrent();
                        if (response.hashtorrent == Torrent.model.listeselectionnee[0]){
                            if ( response.torrentselectionnee){
                                if (response.torrentselectionnee.files ){
                                    var t = Torrent.model.changeselecttorrent && response.torrentselectionnee.files != [] && response.torrentselectionnee.detail != [];
                                    Torrent.controller.conversionListeFiles(response.torrentselectionnee.files,t);
                                    Torrent.controller.conversionListeDetails(response.torrentselectionnee.detail,t);
                                    Torrent.model.changeselecttorrent = false;
                                }

                            }else{
                                //var t = true;
                                //Torrent.controller.conversionListeFiles([],t);
                                //Torrent.controller.conversionListeDetails([],t);
                            }
                        }
                        Torrent.view.detailsTorrent();
                        Torrent.view.filesTorrent();
                        //Torrent.view.listeTorrents(torrent);
                        setTimeout(function(){
                            Torrent.controller.update(res[1]);
                        },1000);
                    }else{
                        Torrent.model.listeoriginal=[];
                        Torrent.model.listeselectionnee=[];
                        Torrent.model.container.listtorrent.empty();
                        Base.view.noty.generate("error","Impossible de se connecter à rtorrent");
                        setTimeout(function(){
                            Torrent.controller.update("");
                        },10000);
                    }
                }else{
                    setTimeout(function(){
                        Torrent.controller.update("");
                    },10000);
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                Torrent.model.changedurl = false;
                Torrent.model.container.listtorrent.empty();
                Torrent.model.updated = false;
                Base.view.noty.generate("error","Impossible de se connecter à "+Torrent.model.nomseedbox);
            }
        });
    },
    detailsTorrent: function(){
        var url = "http://"+Torrent.model.baseUrl+'/torrent/details/'+Torrent.model.listeselectionnee[0]+"/"+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion;

        $.ajax({
            url: url+".json",
            dataType: "json",
            //contentType: "application/json",
            success: function(response, textStatus, jqXHR){
                if ( response.host == Torrent.model.baseUrl){
                    if (response.showdebugger == "ok"){
                        if (Torrent.model.listeselectionnee.length == 1 && response.hashtorrent == Torrent.model.listeselectionnee[0]){
                                if ( response.torrentselectionnee){
                                    Torrent.model.torrentselectionneedetail = {};
                                    if (response.torrentselectionnee.files ){
                                        var t = true;
                                        Torrent.controller.conversionListeFiles(response.torrentselectionnee.files,t);
                                        Torrent.controller.conversionListeDetails(response.torrentselectionnee.detail,t);
                                    }

                                }else{
                                    //var t = true;
                                    //Torrent.controller.conversionListeFiles([],t);
                                    //Torrent.controller.conversionListeDetails([],t);
                                }

                        }
                        Torrent.view.detailsTorrent();
                        Torrent.view.filesTorrent();
                    }else{
                        Base.view.noty.generate("error","Impossible de se connecter à rtorrent");
                    }
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                Base.view.noty.generate("error","Impossible de se connecter à "+Torrent.model.nomseedbox);
            }
        });
    },
    downloadFileTorrent: function(k){
        var url = "http://"+Torrent.model.baseUrl+'/torrent/download/'+Torrent.model.listeselectionnee[0]+"/"+Torrent.model.fileliste[k][0]+"/"+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion;
        if (Torrent.model.fileliste[k][2] == Torrent.model.fileliste[k][3]){
            $("#getdata").attr("action",url).submit();
        }else{
            var text = "Le fichier \""+Base.model.path.basename(Torrent.model.fileliste[k][1])+"\" n'est pas complet<br>Voulez vous continuez le téléchargement ?";
            Base.view.noty.generateConfirm(text,function(){
                $("#getdata").attr("action",url).submit();
            });
        }
        //window.open(url,"_blank", null);
    },
    prioriteFileTorrent : function(priorite){
        var liste = Torrent.model.fileselectionnee;
        var listafaire = [];
        if (liste.length >0){
            $.each(liste, function(k,v){
                listafaire.push(v);
            });
        }
        Torrent.model.fileselectionnee = [];
        $.ajax({
            url: "http://"+Torrent.model.baseUrl+'/torrent/setPrioriteFile/'+Torrent.model.listeselectionnee[0]+"/"+priorite+"/"+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion+".json",
            dataType: "json",
            type: "POST",
            data: {nofiles:listafaire},
            //contentType: "application/json",
            success: function(response, textStatus, jqXHR){
                if (response.showdebugger == "ok"){

                }else{

                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                Base.view.noty.generate("error",textStatus+" "+jqXHR+" "+errorThrown);
            }
        });
    },
    streamingFileTorrent : function(k){
        var url = Torrent.model.downloadFileTorrent(Torrent.model.listeselectionnee[0],Torrent.model.fileselectionnee[0]);
        window.open(Base.model.conf.base_url+'/torrent/streaming/'+Torrent.model.baseUrl+"/"+Torrent.model.listeselectionnee[0]+"/"+Torrent.model.fileselectionnee[0]+"/"+Base.model.path.basename(Torrent.model.fileliste[k][1])+"/test.html","_blank","menubar=no, status=no, scrollbars=no, menubar=no, width=640, height=500");
        //Torrent.view.fileTorrentsStreaming(url);
    },
    torrentPeutEffectuerCommande : function(torrent,commande){
        var ret = true;
        var status = torrent[0];
        var dStatus = Torrent.model.dStatus;
        switch(commande)
        {
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
    },
    addTorrent : {
        addTorrentShow: function(){
            Torrent.view.addTorrent.showAddTorrent()
        },
        addTorrentHide:function(){
            Torrent.view.addTorrent.hideAddTorrent();
        },
        upload : function(e){
            e.preventDefault();
            var formData = new FormData($("#addtorrent")[0]);
            console.log(Base.model.conf.base_url+"/torrent/send/"+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion+".json");
            $.ajax({
                url: "http://"+Torrent.model.baseUrl+'/torrent/send/'+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion+".json",
                async : false,
                //dataType :"json",
                type: "post",
                cache: false,
                contentType: false,
                processData: false,
                data: formData,
                success: function(response, textStatus, jqXHR){
                    //afficheResultat(container,response);
                },
                error: function(jqXHR, textStatus, errorThrown){
                    // afficheErreur(jqXHR.responseText,container);
                }

            });
        },
        checkTorrentFile: function(check){
            if ( check){
                var formData = new FormData($("#addtorrent")[0]);
                $.ajax({
                    url: Base.model.conf.base_url+"torrent/infofichier.json",
                    async : false,
                    //dataType :"json",
                    type: "post",
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: formData,
                    success: function(response, textStatus, jqXHR){
                        //afficheResultat(container,response);
                    },
                    error: function(jqXHR, textStatus, errorThrown){
                        // afficheErreur(jqXHR.responseText,container);
                    }

                });
            }
        }
    }

};