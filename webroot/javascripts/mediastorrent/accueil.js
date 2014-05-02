/**
 * Created with JetBrains PhpStorm.
 * User: Salorium
 * Date: 30/09/13
 * Time: 14:34
 * To change this template use File | Settings | File Templates.
 */

var Accueil = {
    largeurcentralimg: null,
    largeurcentraldiv: null,
    cpt: -1,
    largeurcotediv: null,
    backup: null,
    init: function () {
        inilistener = false;
        console.info("init");
        if (this.backup == null) {
            $(".carrousel")
                // Englobage de la liste par la zone de visualisation
                .wrap('<div class="carrousel-conteneur"></div>');
            this.backup = $("div.carrousel-conteneur").html();
            inilistener = true;

        } else {
            $("div.carrousel-conteneur").empty();
            $("div.carrousel-conteneur").html(this.backup);
        }
        /* $(".carrousel")
         // Englobage de la liste par la zone de visualisation
         .wrap('<div class="carrousel-conteneur"></div>');*/
        //Récupération de la largeur de l'image central soit 30 % de la taille de la fenêtre
        this.largeurcentralimg = $(window).width() * 0.3;
        this.largeurcotediv = $(window).width() * 0.2;
        this.largeurcentraldiv = $(window).width() * 0.6;
        //Englobage de tout les liens pour que l'image soivent centrer verticalement
        $(".carrousel a").wrap('<div class="carrouselimage"></div>');
        //Obliger sinon pas centrer verticalement (Bizarre)
        $(".carrousel div").width(this.largeurcotediv);
        //Initialisation de l'attribut mediastorrent-id
        t = -1;
        $.each($(".carrousel a"), function (k, v) {
            $(v).attr("mediastorrent-id", t).addClass("ct");
            t++;
        });
        //Ajout de la classe centre au premier élément de la liste
        //$(".carrousel li:nth-child(1)").width($(window).width()*0.6);
        $(".carrousel li:nth-child(1) div").width(this.largeurcentraldiv);
        $(".carrousel img").width(this.largeurcentralimg * 0.6);
        $(".carrousel img").css("opacity", 0.5);
        $(".carrousel li:nth-child(1) img").width(this.largeurcentralimg).css("opacity", 1);

        // Nombre d'éléments de la liste
        // Ciblage de la bande de diapositives
        $(".carrousel")
            // Application d'une largeur à la bande de diapositive afin de conserver une structrure horizontale
            .css("width", (this.largeurcotediv * ($(".carrousel li").length - 1 ) + this.largeurcentraldiv))
            .css("height", this.largeurcentralimg)
            .css("margin-left", this.largeurcotediv + "px");

        $(".carrousel-conteneur")
            // Application de la largeur d'une seule diapositive
            .width(this.largeurcotediv * 2 + this.largeurcentraldiv)
            // Application de la hauteur d'une seule diapositive
            .height(this.largeurcentralimg)
            // Blocage des débordements
            .css("overflow", "hidden").css({"top": $(window).height() / 2 - this.largeurcentralimg / 2 + "px", "position": "relative"});
        this.listener();
        if (inilistener) {

            this.resize();
        }
    },

    listener: function () {
        var o = this;
        $(".ct").mouseover(function (event) {
            event.preventDefault();
            var a = $(this);
            var annimate = false;
            if (a.attr("mediastorrent-id") > o.cpt) {
                o.cpt++;
                annimate = true;

            } else if (a.attr("mediastorrent-id") < o.cpt) {
                o.cpt--;
                annimate = true;
            }
            if (annimate) {
                $(".carrousel img").animate({
                    opacity: 0

                }, 250);
                $(".carrouselimage").animate({
                    width: o.largeurcotediv

                }, 250);
                $(".carrousel li:nth-child(" + (o.cpt + 2) + ") div").animate({
                    width: o.largeurcentraldiv

                }, 250);
                $(".carrousel li:nth-child(" + (o.cpt + 3) + ") img").animate({
                    width: o.largeurcentralimg * 0.6,
                    opacity: 0.5
                }, 250);
                $(".carrousel li:nth-child(" + (o.cpt + 1) + ") img").animate({
                    width: o.largeurcentralimg * 0.6,
                    opacity: 0.5
                }, 250);
                $(".carrousel li:nth-child(" + (o.cpt + 2) + ") img").animate({
                    width: o.largeurcentralimg,
                    opacity: 1
                }, 250);
                console.info("Cpt " + o.cpt);
                $(".carrousel").animate({
                    marginLeft: -(o.largeurcotediv * o.cpt)
                }, 250);
            }
        });
    },

    resize: function () {
        var o = this;
        $(window).resize(function () {
            o.init();
        });
    }


}