/**
 * Version 1.0
 * @author Salorium
 **/
var timer;
var lastY;
$(document).ready(function () {
    $(document).on('click', ".btcontroll", function (event) {
        event.preventDefault();
        var a = $(this);
        /*history.pushState({type :a.attr("type_replay"),methode:a.attr("methode_replay"),id :a.attr("id_replay")},a.attr("type_replay"),"?type="+a.attr("type_replay")+"&id="+a.attr("id_replay")+"&methode="+a.attr("methode_replay"));
         load = false;
         switch (a.attr("methode_replay")){
         case "chaine":
         load = true;
         break;
         default:
         if( M6Group.type != a.attr("type_replay") ){ load = true;}
         break;
         }
         M6Group.load(a.attr("type_replay"),a.attr("methode_replay"),load,a.attr("id_replay"));*/
        Film.genereControlTopBar(parseInt(a.attr("mediastorrent_id")), true);
        console.info("tt");
    });
    $(document).on('mousewheel DOMMouseScroll', '.btcontroll', function (e) {
        e.preventDefault();
        delta = e.originalEvent.detail
        if (e.originalEvent.wheelDelta)
            delta = e.originalEvent.wheelDelta * -1;
        console.info(delta);
        if (delta < 0) {
            Film.compteur--;
            if (Film.compteur < 1)
                Film.compteur = 1;
        } else {
            Film.compteur++;

            if (Film.compteur >= Film.tonObjet.length) {
                Film.compteur = Film.tonObjet.length;
            }
        }
        Film.genereControlTopBar(Film.compteur, true);

    });
    $(document).on('mouseenter', ".btcontrol", function (event) {
        event.preventDefault();
        var a = $(this);
        /*history.pushState({type :a.attr("type_replay"),methode:a.attr("methode_replay"),id :a.attr("id_replay")},a.attr("type_replay"),"?type="+a.attr("type_replay")+"&id="+a.attr("id_replay")+"&methode="+a.attr("methode_replay"));
         load = false;
         switch (a.attr("methode_replay")){
         case "chaine":
         load = true;
         break;
         default:
         if( M6Group.type != a.attr("type_replay") ){ load = true;}
         break;
         }
         M6Group.load(a.attr("type_replay"),a.attr("methode_replay"),load,a.attr("id_replay"));*/

        console.info("tt");

        timer = setInterval(function () {

            Film.genereControlTopBar(parseInt(a.attr("mediastorrent_id")), true);
        }, 100);


    });
    $(document).on('mouseleave', ".btcontrol", function (event) {
        event.preventDefault();
        var a = $(this);
        /*history.pushState({type :a.attr("type_replay"),methode:a.attr("methode_replay"),id :a.attr("id_replay")},a.attr("type_replay"),"?type="+a.attr("type_replay")+"&id="+a.attr("id_replay")+"&methode="+a.attr("methode_replay"));
         load = false;
         switch (a.attr("methode_replay")){
         case "chaine":
         load = true;
         break;
         default:
         if( M6Group.type != a.attr("type_replay") ){ load = true;}
         break;
         }
         M6Group.load(a.attr("type_replay"),a.attr("methode_replay"),load,a.attr("id_replay"));*/

        console.info("tt");

        clearInterval(timer);

        Film.genereControlTopBar(parseInt(a.attr("mediastorrent_id")), true);

    });

    $(window).resize(function () {
        // Film.init();
    });

});
var Film = {
    tr: [],
    hauteurWindows: 0,
    largeurWindows: 0,
    percentageHauteurControl: 30,
    percentageArrondi: 13,
    pixelArrondi: 0,
    largeurReferenceControl: 154,
    hauteurReferenceControl: 231,
    largeurControl: 0,
    hauteurControl: 0,
    hauteurRefletControl: 0,
    topControl: 0,
    qualiteposter: "",
    qualitebackdrop: "",
    tonObjet: [],
    tonObjet1: [],
    compteur: 0,
    container: null,
    nombreControlDansLargeur: 0,
    borneControlPartieGauche: 0,
    borneControlPartieCentral: 0,
    containerControl: null,
    containerBtControl: null,
    containerCss: null,
    containerBtG: null,
    containerBtD: null,
    containerBande: null,
    interval: [],
    containerDetailsFilm: null,
    CssModulable: "",
    zindex: 1,
    time: null,
    time1: null,
    init: function (all) {
        console.log("INIT");
        if (this.tonObjet.length == 0) {
            this.tonObjet = all;
            $.each(this.tonObjet, function (k, v) {
                //console.log(v.id);
                Film.tonObjet1[v.id] = k + 1;
            })
        }
        if (this.container) {
            $(this.container).remove();
        }
        if ($(window).height() < 750 || $(window).width() < 960) {
            console.info("Mosaique");
            //$(".container").append("<img id='t' src='"+ Base.controller.makeUrlBase() + "film/getPosterSetWidth/" + Base.model.converter.paramUrl(this.tonObjet[1].id) + "/"+$(window).width() +".jpg'>");

            this.mobileAffiche(0);
        } else {
            this.initTopBar();
        }
    },
    mobileAffiche: function (id) {
        $(".container").empty();
        $img = $("<div></div>");
        $img.css({
            "background": 'url("' + Base.controller.makeUrlBase() + "serie/getPosterSetWidth/" + Base.model.converter.paramUrl(this.tonObjet[id].id) + '/1920.jpg") center center fixed',
            "background-size": "cover",
            "height": ( $(window).height() - 45) + 'px'
        });
        $(".container").append($img);
        $img.bind('touchstart', function (e) {
            lastX = e.originalEvent.touches[0].clientX;
        });
        $img.bind('touchmove', function (e) {
            if (lastX > e.originalEvent.touches[0].clientX) {
                console.log("gauche");
                var t = id + 1;
                if (t > Film.tonObjet.length - 1)
                    t = Film.tonObjet.length - 1;

            } else {
                console.log("droite");
                var t = id - 1;
                if (t < 0)
                    t = 0;

            }
            Film.mobileAffiche(t);

        });
        $img.append($("<a style='background-color: #ffffff'></a>").append(this.tonObjet[id].Titre));
        if (this.time)this.time.abort();
        this.time = $.ajax({
            url: Base.controller.makeUrlBase() + 'serie/getFile/' + this.tonObjet[id].id + ".json",
            dataType: "json",
            type: "GET",
            //data: {hash: listafaire},
            //contentType: "application/json",
            success: function (response, textStatus, jqXHR) {
                $table = $("<tbody></tbody>");
                $img.append($("<table></table>").append($table));
                if (response.showdebugger == "ok") {
                    $.each(response.file, function (k, v) {
                        //console.log(v);
                        if (v.fini == 1) {
                            $table.append('<tr><td>' + v.mediainfo.typequalite + (v.mediainfo.qualite ? " " + v.mediainfo.qualite : "" ) + '</td><td>' + (v.mediainfo.codec ? v.mediainfo.codec : "" ) + '</td><td>' + (v.mediainfo.audios[0].type ? v.mediainfo.audios[0].type : "" ) + '</td><td>' + (v.complementfichier ? v.complementfichier : "" ) + '</td><td><a href="' + Base.controller.makeUrlBase(v.hostname) + 'film/download/' + v.id + '/' + Base.model.utilisateur.keyconnexion + '"><img width="60" src="' + Base.controller.makeUrlBase() + 'images/dl.svg"></a></td></tr>');
                        } else {
                            $tr = $("<tr></tr>").append("<td>Attente...</td>");
                            Film.tr.push($tr);
                            $table.append($tr);
                            Film.interval.push(setInterval(Film.test1, 1000, Film.tr.length - 1, v.hostname, v.id));
                        }
                    });
                    /*for (var i = 0; i < 2; i++) {
                     if (i == 0) {
                     Film.tr[i] = $("<tr></tr>").append("<td>0</td>");

                     } else {
                     Film.tr[i] = $("<tr></tr>").append("<td>100</td>");

                     }
                     $table.append(Film.tr[i]);
                     Film.interval.push(setInterval(Film.test, 1000, i, i));
                     }*/


                } else {

                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus != "abort")
                    Base.view.noty.generate("error", textStatus + " " + jqXHR + " " + errorThrown);

            }
        });

    },
    initTopBar: function () {
        this.container = $('<div></div>').appendTo(".container");
        this.container.append('<div style="height: 1px;"></div>')
        this.containerDetailsFilm = $('<div id="detailsFilm" class="detailsFilm"></div>').appendTo(this.container);
        $div = $('<div></div>');
        this.cdetail = $('<div class="large-6 columns"></div> ');
        $div.append(this.cdetail);
        $div.append(this.cdetail);
        this.containerDetailsFilm.append($div);
        this.containerControl = $('<div></div>').attr({
            "class": "control"
        }).appendTo(this.container);
        this.containerCss = $('<style type="text/css">').appendTo(this.container);
        this.containerBtG = $('<div class="btcontrol btcontroll"></div>').appendTo(this.containerControl);
        this.containerBtControl = $('<div style="position relative; float : left;"></div>').appendTo(this.containerControl);
        this.containerBtD = $('<div class="btcontrol btcontroll"></div>').appendTo(this.containerControl);
        $('<svg height="0"> <mask id="mask1"> <rect id="rec1" width="100%" height="' + this.hauteurRefletControl + '" fill="url(#gr)"/> <linearGradient x1="0" y1="0" x2="0" y2="1" id="gr"> <stop offset="10%" stop-color="black" /> <stop offset="100%" stop-color="white" /> </linearGradient> </mask> </svg>').appendTo(this.container);
        //this.containerBande = $('<div style="position :absolute; width : 100%; background : #999; height:10px; bottom : 0; "></div>').appendTo(this.containerControl);
        this.prepareGenereControlTopBar();
    },
    addUniqueCssGaucheTopBar: function (id) {
        this.CssModulable += "div#controlbt" + id + " {" + "float : left;" + "position: relative;" + "width:" + this.largeurControl + "px;" + "height:" + this.hauteurControl + "px;" + "-webkit-box-reflect: below 0 linear-gradient(transparent 75%, rgba(0,0,0,.3));" + "-webkit-box-shadow: rgba(255, 255, 255, 1) 0 0px 10px, rgba(0, 128, 0, 0.2) 0 0 3px 1px inset;" + "-moz-box-shadow: rgba(255, 255, 255, 1) 0 0px 10px, rgba(0, 128, 0, 0.2) 0 0 3px 1px inset;" + "box-shadow: rgba(255, 255, 255, 1) 0 0px 10px, rgba(0, 128, 0, 0.2) 0 0 3px 1px inset;" + "-webkit-border-radius: " + this.pixelArrondi + "px;" + "-moz-border-radius: " + this.pixelArrondi + "px;" + "-ms-border-radius: " + this.pixelArrondi + "px;" + "border-radius: " + this.pixelArrondi + "px;" + "}" + "div#controlbt" + id + "::after {" + "mask: url(#mask1);" + "content: '';" + "position: absolute;" + "left: 0;" + "top: 100%;" + "width: inherit;" + "height:" + this.hauteurRefletControl + "px;" + "background: -moz-element(#controlbt" + id + ") bottom;" + "transform: scaleY(-1);" + "opacity: .3;" + "-webkit-border-radius: " + this.pixelArrondi + "px;" + "-moz-border-radius: " + this.pixelArrondi + "px;" + "-ms-border-radius: " + this.pixelArrondi + "px;" + "border-radius: " + this.pixelArrondi + "px;" + "}";
    },
    addUniqueCssDroiteTopBar: function (id) {
        this.CssModulable += "div#controlbt" + id + " {" + "float : left;" + "position: relative;" + "width:" + this.largeurControl + "px;" + "height:" + this.hauteurControl + "px;" + "-webkit-box-reflect: below 0 linear-gradient(transparent 80%, rgba(0,0,0,.3));" + "-webkit-box-shadow: rgba(255, 255, 255, 1) 0 0px 10px, rgba(0, 128, 0, 0.2) 0 0 3px 1px inset;" + "-moz-box-shadow: rgba(255, 255, 255, 1) 0 0px 10px, rgba(0, 128, 0, 0.2) 0 0 3px 1px inset;" + "box-shadow: rgba(255, 255, 255, 1) 0 0px 10px, rgba(0, 128, 0, 0.2) 0 0 3px 1px inset;" + "-webkit-border-radius: " + this.pixelArrondi + "px;" + "-moz-border-radius: " + this.pixelArrondi + "px;" + "-ms-border-radius: " + this.pixelArrondi + "px;" + "border-radius: " + this.pixelArrondi + "px;" + "transform: scaleX(-1);" + "-webkit-transform : scaleX(-1);" + "}" + "div#controlbt" + id + "::after {" + "mask: url(#mask1);" + "content: '';" + "position: absolute;" + "left: 0;" + "top: 100%;" + "width: inherit;" + "height:" + this.hauteurRefletControl + "px;" + "background: -moz-element(#controlbt" + id + ") bottom;" + "transform: scaleY(-1) scaleX(-1); " + "opacity: .3;" + "-webkit-border-radius: " + this.pixelArrondi + "px;" + "-moz-border-radius: " + this.pixelArrondi + "px;" + "-ms-border-radius: " + this.pixelArrondi + "px;" + "border-radius: " + this.pixelArrondi + "px;" + "}";
    },
    genereCssRotationTopBar: function () {
        return ".rot:hover{" + "transform: translateX(-" + this.demiLargeurControl * 0.9 + "px) translateZ(50px) rotateY(20deg);" + "-webkit-transform: translateX(-" + this.demiLargeurControl * 0.9 + "px) translateZ(50px) rotateY(20deg);" + "}" + ".rot1:hover{" + "transform: translateX(" + this.demiLargeurControl * 0.7 + "px) translateZ(50px) rotateY(150deg);" + "-webkit-transform:translateX(" + this.demiLargeurControl * 0.7 + "px)translateZ(50px)rotateY(150deg);" + "}";
    },
    prepareGenereControlTopBar: function () {
        this.CssModulable = "";
        this.hauteurWindows = $(window).height();
        this.hauteurWindows = this.hauteurWindows - 75;
        $(".container").css("height", this.hauteurWindows + 20);
        console.info(this.hauteurWindows);
        this.largeurWindows = $(".container").width();
        this.hauteurControl = Math.round(this.percentageHauteurControl * this.hauteurWindows / 100);
        this.largeurControl = Math.round(this.hauteurControl * this.largeurReferenceControl / this.hauteurReferenceControl);
        id = 0;
        ok = false;
        /*while (id < this.tonObjet.poster_sizes.length && !ok) {
         var tmp = this.tonObjet.poster_sizes[id];
         var patt1 = /([0-9])+/g;
         var tmp1 = parseInt(tmp.match(patt1));
         if (this.largeurControl < tmp1) {
         ok = true;
         } else {
         id++;
         }
         }
         this.qualiteposter = this.tonObjet.poster_sizes[id];
         id = 0;
         ok = false;
         while (id < this.tonObjet.backdrop_sizes.length && !ok) {
         var tmp = this.tonObjet.backdrop_sizes[id];
         var patt1 = /([0-9])+/g;
         var tmp1 = parseInt(tmp.match(patt1));
         if (this.largeurWindows < tmp1) {
         ok = true;
         } else {
         id++;
         }
         }
         if (id >= this.tonObjet.backdrop_sizes.length) id--;*/
        //this.qualitebackdrop = this.tonObjet.backdrop_sizes[id];
        this.demiLargeurControl = Math.round(this.largeurControl / 2);
        this.hauteurRefletControl = Math.round(this.hauteurControl / 4);
        this.topControl = this.hauteurWindows - this.hauteurControl - this.hauteurRefletControl;
        this.pixelArrondi = Math.round(this.largeurControl * this.percentageArrondi / 100);
        this.nombreControlDansLargeur = Math.floor((this.largeurWindows - 200 - this.largeurControl) / this.demiLargeurControl) + 1;
        if (this.tonObjet.length <= this.nombreControlDansLargeur) this.nombreControlDansLargeur = this.tonObjet.length;
        this.borneControlPartieGauche = Math.floor(this.nombreControlDansLargeur / 2);
        this.borneControlPartieCentral = this.borneControlPartieGauche + 1;
        this.containerCss.append(this.genereCssRotationTopBar());
        this.containerDetailsFilm.css({
            "height": this.topControl - 80 + "px"
        });

        this.containerControl.css({
            "top": 15 + "px",
            "height": (this.hauteurControl + this.hauteurRefletControl) + "px"
        });
        //this.qualitebackdrop = "w780";
        $("#rec1").attr("height", this.hauteurRefletControl);
        this.containerControl.css({
            "width": ((this.nombreControlDansLargeur - 1) * this.demiLargeurControl + 200 + this.largeurControl) + "px"
        });
        /*this.containerBande.css({
         //"height": (this.hauteurRefletControl * 2) + "px"
         });*/
        this.containerBtControl.css({
            "width": ((this.nombreControlDansLargeur - 1) * this.demiLargeurControl + this.largeurControl) + "px"
        });
        Base.view.loader.make("detailsFilm");
        this.containerDetailsFilm.hide();
        if (window.location.hash.substr(1) != "") {
            this.genereControlTopBarId(window.location.hash.substr(1));
        } else {
            this.genereControlTopBar(null, true);
        }
    },
    genereControlTopBarId: function (id) {
        if (this.tonObjet1[id] != null) {
            this.genereControlTopBar(this.tonObjet1[id], true);
        }
    },
    genereControlTopBar: function (milieux, screenshot) {
        var central = this.borneControlPartieCentral;
        if (milieux != null) {
            if (0 <= milieux && milieux <= this.tonObjet.length) {
                central = milieux;

            } else {
                this.genereControlTopBar(null, true);
            }
        }
        this.CssModulable = this.genereCssRotationTopBar();
        this.containerBtControl.empty();
        this.containerCss.empty();
        this.zindex = this.nombreControlDansLargeur;
        btg = central - 1;
        if (btg < 1) {
            btg = 1;
        }
        btd = central + 1;
        console.info(btd);
        if (btd > this.tonObjet.length) {
            btd = this.tonObjet.length;
        }
        this.containerBtG.attr("mediastorrent_id", btg);
        this.containerBtD.attr("mediastorrent_id", btd);
        var o = this;
        id = central - this.borneControlPartieCentral;
        if (central - this.borneControlPartieCentral < 0) id = 0;
        max = id + this.nombreControlDansLargeur;
        if (max > this.tonObjet.length) max = this.tonObjet.length;
        if (max - id < this.nombreControlDansLargeur) {
            id = max - this.nombreControlDansLargeur;
        }
        //Film.compteur = central;
        //console.info("Nombre controle dans la largeur : " + this.nombreControlDansLargeur + " Nb film " + this.tonObjet.film.length + " id " + id + " max " + max);
        console.log(this.tonObjet.length)
        console.log(max > this.tonObjet.length);
        console.log(this.tonObjet.length);
        while (id < max) {
            this.afficheControlTopBar(this.tonObjet[id], id + 1, central, screenshot);
            id++;
        }
        this.containerCss.append(this.CssModulable);
    },
    afficheControlTopBar: function (control, id, centre, screenshot) {
        console.log(control);
        if (id <= centre - 1) {
            /*if (control.poster) {
             this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.demiLargeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="' + Base.controller.makeUrlBase() + "proxy/imageSetWidth/" + Base.model.converter.paramUrl(control.poster) + '/200.jpg" alt="' + control.Titre + '"></a></div> </div></div>');
             } else {
             this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.demiLargeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="' + Base.controller.makeUrlBase() + "proxy/noimage/" + Base.model.converter.paramUrl(control.Titre) + '.jpg"' + '" alt="' + control.Titre + '"></a></div> </div></div>');
             }*/
            this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.demiLargeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="' + Base.controller.makeUrlBase() + "serie/getPosterSetWidth/" + Base.model.converter.paramUrl(control.id) + '/200.jpg" alt="' + control.Titre + '"></a></div> </div></div>');
            this.zindex++;
            this.addUniqueCssGaucheTopBar(id);
        } else if (id <= centre) {
            /*if (control.poster) {
             this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.largeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot2"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="' + Base.controller.makeUrlBase() + "proxy/imageSetWidth/" + Base.model.converter.paramUrl(control.poster) + '/200.jpg" alt="' + control.Titre + '"></a></div> </div></div>');
             } else {
             this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.largeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot2"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="' + Base.controller.makeUrlBase() + "proxy/noimage/" + Base.model.converter.paramUrl(control.Titre) + '.jpg" alt="' + control.Titre + '"></a></div> </div></div>');
             }*/
            window.location.hash = control.id;
            this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.largeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot2"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="' + Base.controller.makeUrlBase() + "serie/getPosterSetWidth/" + Base.model.converter.paramUrl(control.id) + '/200.jpg" alt="' + control.Titre + '"></a></div> </div></div>');
            if (control.backdrop && screenshot) {
                console.log(Base.model.converter.paramUrl(control.backdrop));
                $("#background").css({
                    "background": 'url("' + Base.controller.makeUrlBase() + "serie/getBackdropSetWidth/" + Base.model.converter.paramUrl(control.id) + '/1920.jpg") center center fixed',
                    "background-size": "cover"
                });
                console.log($('html'));
            } else {
                $("#background").css("background-image", 'none');
            }
            this.zindex--;
            this.afficheDetailsFilm(control);
            this.addUniqueCssGaucheTopBar(id);
        } else {
            /*if (control.poster) {
             this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.demiLargeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot1"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="' + Base.controller.makeUrlBase() + "proxy/imageSetWidth/" + Base.model.converter.paramUrl(control.poster) + '/200.jpg" alt="' + control.Titre + '"></a></div> </div></div>');
             } else {
             this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.demiLargeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot1"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="' + Base.controller.makeUrlBase() + "proxy/noimage/" + Base.model.converter.paramUrl(control.Titre) + '.jpg" alt="' + control.Titre + '"></a></div> </div></div>');

             }*/
            this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.demiLargeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot1"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="' + Base.controller.makeUrlBase() + "serie/getPosterSetWidth/" + Base.model.converter.paramUrl(control.id) + '/200.jpg" alt="' + control.Titre + '"></a></div> </div></div>');
            this.zindex--;
            this.addUniqueCssDroiteTopBar(id);

        }

    },
    streaming: function (k, host) {
        window.open(Base.controller.makeUrlBase(host) + 'serie/streaming/' + Base.model.converter.paramUrl(k) + ".html", "_blank", "menubar=no, status=no, scrollbars=no, toolbar=no,location=no,resizable=no, width=650, height=510");
    },
    afficheDetailsFilm: function (film) {
        this.clean();
        this.containerDetailsFilm.show();
        $fieldset = $('<fieldset><legend>' + film.Titre + '</legend></fieldset>');
        this.containerDetailsFilm.empty();
        this.containerDetailsFilm.append($fieldset);
        urlimg = Base.controller.makeUrlBase() + "serie/getPosterSetHeight/" + Base.model.converter.paramUrl(film.id) + "/" + (this.topControl - 180) + '.jpg';
        /*if (film.poster) {
         urlimg = Base.controller.makeUrlBase() + "proxy/imageSetHeight/" + Base.model.converter.paramUrl(film.poster) + '/' + (this.topControl - 180) + '.jpg';
         }*/
        $divimg = $('<div class="float" style="margin-right: 10px;width: 20%;"> <img style="height:' + (this.topControl - 180) + 'px;" src="' + urlimg + '" alt="' + film.Titre + '"></div>');
        $fieldset.append($divimg);
        console.log((this.containerDetailsFilm.width() - $divimg.width()));
        $divv = $('<div class="float" style="width: 78%;"></div>');
        $row1 = $('<div style="width: 100%;display: table;"></div>');
        $divv1 = $('<div class="float" style="width: 50%;"></div>');
        $divv2 = $('<div class="float" style="width: 50%; overflow:auto;"></div>');

        $divv.append($row1);
        $row1.append($divv1);
        $row1.append($divv2);

        //$div.append($divv);

        $.each(film, function (k, v) {
            if (/^[A-Z]/.test(k)) {
                switch (k) {
                    case "Titre original":
                    case "Acteur(s)":
                    case "Réalisateur(s)":
                    case "Genre":
                    case "Durée":
                        $divv1.append('<span>' + k + ' : ' + v + '</span><br><br>');
                        break;
                }

            }
        });
        $fieldset.append($divv);
        //$fieldset.append('<div>'+film['Synopsis']+'</div>');
        heigh = $divv.height();
        $divv2.height(heigh);
        console.log(heigh);
        $divv.append('<div style="height: ' + (this.topControl - 180 - heigh ) + 'px; overflow:auto;">Synopsis : <p>' + film["Synopsis"] + '</p></div>');
        $tbody = $('<tbody></tbody>');
        //$divv.append($('<div style="height: 100px; overflow: auto;"></div> ').append($('<table style="width: 100%;"></table>').append($tbody)));
        if (this.time)this.time.abort();
        this.time = $.ajax({
            url: Base.controller.makeUrlBase() + 'serie/getSaison/' + film.id + ".json",
            dataType: "json",
            type: "GET",
            //data: {hash: listafaire},
            //contentType: "application/json",
            success: function (response, textStatus, jqXHR) {
                $table = $("<div></div>");
                $divv2.append($table);
                if (response.showdebugger == "ok") {
                    //var id=response.idserie;
                    $.each(response.file, function (k, v) {
                        //console.log(v);
                        $table.append('<a onclick="Film.episode(\'' + film.id + "','" + v.saison + '\')" class="button">Saison ' + v.saison + '</a>');

                        /*if (v.fini == 1) {
                            $table.append('<tr><td>' + v.mediainfo.typequalite + (v.mediainfo.qualite ? " " + v.mediainfo.qualite : "" ) + '</td><td>' + (v.mediainfo.codec ? v.mediainfo.codec : "" ) + '</td><td>' + (v.mediainfo.audios[0].type ? v.mediainfo.audios[0].type : "" ) + '</td><td>' + (v.complementfichier ? v.complementfichier : "" ) + '</td><td><a href="' + Base.controller.makeUrlBase(v.hostname) + 'serie/download/' + v.id + '/' + Base.model.utilisateur.keyconnexion + '"><img width="30" src="' + Base.controller.makeUrlBase() + 'images/dl.svg"></a></td><td><a onclick="Film.streaming(\'' + v.id + '\',\'' + v.hostname + '\')"><img width="30" src="' + Base.controller.makeUrlBase() + 'images/streaming.svg"></a></td></tr>');
                        } else {
                            $tr = $("<tr></tr>").append("<td>Attente...</td>");
                            Film.tr.push($tr);
                            $table.append($tr);
                            Film.interval.push(setInterval(Film.test, 1000, Film.tr.length - 1, v.hostname, v.id));
                         }*/
                    });
                    /*for (var i = 0; i < 2; i++) {
                     if (i == 0) {
                     Film.tr[i] = $("<tr></tr>").append("<td>0</td>");

                     } else {
                     Film.tr[i] = $("<tr></tr>").append("<td>100</td>");

                     }
                     $table.append(Film.tr[i]);
                     Film.interval.push(setInterval(Film.test, 1000, i, i));
                     }*/


                } else {

                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus != "abort")
                    Base.view.noty.generate("error", textStatus + " " + jqXHR + " " + errorThrown);

            }
        });

    },
    episode: function (idserie, saison) {
        //if (this.time1)this.time1.abort();
        $.ajax({
            url: Base.controller.makeUrlBase() + 'serie/getFileParSaison/' + idserie + '/' + saison + ".json",
            dataType: "json",
            type: "GET",
            //data: {hash: listafaire},
            //contentType: "application/json",
            success: function (response, textStatus, jqXHR) {
                //$table = $("<div></div>");
                //$divv2.append($table);
                if (Base.model.boxmodal.modal == null) {
                    Base.view.boxmodal.make("Saison " + saison, "<div id='episodecontenu' style='overflow: auto'><table><tbody id='episode'></tbody></table></div>", function () {
                        clearTimeout(Film.time1);
                    });
                    $("#episodecontenu").height($("#modalc").height() - $("#modaltitre").height() - $("#modalcontenu").height())
                }
                $("#episode").empty();
                if (response.showdebugger == "ok") {

                    //var id=response.idserie;
                    $.each(response.file, function (k, v) {
                        //console.log(v);
                        //    $table.append('<a onclick="Film.episode(\''+film.id+"','"+v.saison+'\')" class="button">Saison '+ v.saison+'</a>');

                        if (v.fini == 1) {
                            $("#episode").append('<tr><td>Épisode ' + v.episode + '</td><td>' + v.mediainfo.typequalite + (v.mediainfo.qualite ? " " + v.mediainfo.qualite : "" ) + '</td><td>' + (v.mediainfo.codec ? v.mediainfo.codec : "" ) + '</td><td>' + (v.mediainfo.audios[0].type ? v.mediainfo.audios[0].type : "" ) + '</td><td>' + (v.complementfichier ? v.complementfichier : "" ) + '</td><td>' + Base.model.converter.bytes(v.mediainfo.taille, 2) + '</td><td><a href="' + Base.controller.makeUrlBase(v.hostname) + 'serie/download/' + v.id + '/' + Base.model.utilisateur.keyconnexion + '"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="30px" height="30px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path fill="black" id="download-9-icon" d="M360.353,277.271L256.998,380.802L153.647,277.271h47.529v-38.77h111.646v38.77H360.353z M201.177,220.49h111.646v-31.318H201.177V220.49z M312.823,172.729v-25.53H201.177v25.53H312.823z M90,256c0,91.756,74.258,166,166,166c91.755,0,166-74.258,166-166c0-91.755-74.258-166-166-166C164.245,90,90,164.259,90,256z M462,256c0,113.771-92.229,206-206,206S50,369.771,50,256c0-113.771,92.229-206,206-206S462,142.229,462,256z"/></svg></a></td><td><a onclick="Film.streaming(\'' + v.id + '\',\'' + v.hostname + '\')"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"                            width="30px" height="30px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"> <path fill="black" id="video-play-3-icon" d="M256,92.481c44.433,0,86.18,17.068,117.553,48.064C404.794,171.411,422,212.413,422,255.999                            s-17.206,84.588-48.448,115.455c-31.372,30.994-73.12,48.064-117.552,48.064s-86.179-17.07-117.552-48.064                            C107.206,340.587,90,299.585,90,255.999s17.206-84.588,48.448-115.453C169.821,109.55,211.568,92.481,256,92.481 M256,52.481c-113.771,0-206,91.117-206,203.518c0,112.398,92.229,203.52,206,203.52c113.772,0,206-91.121,206-203.52C462,143.599,369.772,52.481,256,52.481L256,52.481z M206.544,357.161V159.833l160.919,98.666L206.544,357.161z"/> </svg></a></td></tr>');
                        } else {
                            $("#episode").append('<tr><td colspan="6">Épisode ' + v.episode + '</td><td colspan="2">' + (response.time[v.clefunique].timerestant != -1 ? Base.model.converter.time(response.time[v.clefunique].timerestant) : "∞") + '</td></tr>');

                        }
                    });
                    if (!response.fini) {
                        Film.time1 = setTimeout(function () {
                            Film.episode(idserie, saison);
                        }, 1000);
                    }
                    /*for (var i = 0; i < 2; i++) {
                     if (i == 0) {
                     Film.tr[i] = $("<tr></tr>").append("<td>0</td>");

                     } else {
                     Film.tr[i] = $("<tr></tr>").append("<td>100</td>");

                     }
                     $table.append(Film.tr[i]);
                     Film.interval.push(setInterval(Film.test, 1000, i, i));
                     }*/


                } else {

                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus != "abort")
                    Base.view.noty.generate("error", textStatus + " " + jqXHR + " " + errorThrown);

            }
        });
    },
    test: function (element, host, id) {
        $.ajax({
            url: Base.controller.makeUrlBase(host) + 'serie/getTime/' + Base.model.utilisateur.keyconnexion + "/" + id + ".json",
            dataType: "json",
            type: "GET",
            //data: {hash: listafaire},
            //contentType: "application/json",
            success: function (response, textStatus, jqXHR) {
                if (response.showdebugger == "ok") {
                    //response.file
                    //console.log(v);
                    if (response.file.fini == 1) {
                        Film.tr[element].html('<td>' + response.file.mediainfo.typequalite + (response.file.mediainfo.qualite ? " " + response.file.mediainfo.qualite : "" ) + '</td><td>' + (response.file.mediainfo.codec ? response.file.mediainfo.codec : "" ) + '</td><td>' + (response.file.mediainfo.audios[0].type ? response.file.mediainfo.audios[0].type : "" ) + '</td><td>' + (response.file.complementfichier ? response.file.complementfichier : "" ) + '</td><td><a href="' + Base.controller.makeUrlBase(response.file.hostname) + 'serie/download/' + response.file.id + '/' + Base.model.utilisateur.keyconnexion + '"><img width="30" src="' + Base.controller.makeUrlBase() + 'images/dl.svg"></a></td><td><a onclick="Film.streaming(\'' + response.file.id + '\',\'' + response.file.hostname + '\')"><img width="30" src="' + Base.controller.makeUrlBase() + 'images/streaming.svg"></a></td>');
                        clearInterval(Film.interval[element]);
                    } else {
                        Film.tr[element].html('<td colspan="4">' + response.file.nomtorrent + '</td><td colspan="2">' + (response.file.timerestant != -1 ? Base.model.converter.time(response.file.timerestant) : "∞") + '</td>');
                    }


                } else {
                    Base.view.noty.generate("error", response);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus != "abort")
                    Base.view.noty.generate("error", textStatus + " " + jqXHR + " " + errorThrown);

            }
        });
    },
    test1: function (element, host, id) {
        $.ajax({
            url: Base.controller.makeUrlBase(host) + 'serie/getTime/' + Base.model.utilisateur.keyconnexion + "/" + id + ".json",
            dataType: "json",
            type: "GET",
            //data: {hash: listafaire},
            //contentType: "application/json",
            success: function (response, textStatus, jqXHR) {
                if (response.showdebugger == "ok") {
                    //response.file
                    //console.log(v);
                    if (response.file.fini == 1) {
                        Film.tr[element].html('<td>' + response.file.mediainfo.typequalite + (response.file.mediainfo.qualite ? " " + response.file.mediainfo.qualite : "" ) + '</td><td>' + (response.file.mediainfo.codec ? response.file.mediainfo.codec : "" ) + '</td><td>' + (response.file.mediainfo.audios[0].type ? response.file.mediainfo.audios[0].type : "" ) + '</td><td>' + (response.file.complementfichier ? response.file.complementfichier : "" ) + '</td><td><a href="' + Base.controller.makeUrlBase(response.file.hostname) + 'serie/download/' + response.file.id + '/' + Base.model.utilisateur.keyconnexion + '"><img width="60" src="' + Base.controller.makeUrlBase() + 'images/dl.svg"></a></td>');
                        console.log('TEST1');
                        clearInterval(Film.interval[element]);
                    } else {
                        Film.tr[element].html('<td colspan="4">' + response.file.nomtorrent + '</td><td colspan="2">' + (response.file.timerestant != -1 ? Base.model.converter.time(response.file.timerestant) : "∞") + '</td>');
                    }


                } else {
                    Base.view.noty.generate("error", response);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                if (textStatus != "abort")
                    Base.view.noty.generate("error", textStatus + " " + jqXHR + " " + errorThrown);

            }
        });
    },
    clean: function () {
        $.each(this.interval, function (k, v) {
            clearInterval(v);

        });
    }
}