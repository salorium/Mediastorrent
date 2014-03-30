/**
 * Created by salorium on 15/03/14.
 */
Accueil.controller =  {

    init: function () {
        Accueil.model.inilistener = false;
        console.info("init");
        if (Accueil.model.backup == null){
            $(".carrousel")
                .wrap('<div class="carrousel-conteneur"></div>');
            Accueil.model.backup = $("div.carrousel-conteneur").html();
            Accueil.model.inilistener = true;

        }else{
            $("div.carrousel-conteneur").empty();
            $("div.carrousel-conteneur").html(Accueil.model.backup);
        }
        /* $(".carrousel")
         // Englobage de la liste par la zone de visualisation
         .wrap('<div class="carrousel-conteneur"></div>');*/
        //Récupération de la largeur de l'image central soit 30 % de la taille de la fenêtre
        Accueil.model.largeurcentralimg = $(window).width() * 0.3;
        Accueil.model.largeurcotediv = $(window).width() * 0.2;
        Accueil.model.largeurcentraldiv = $(window).width() * 0.6;
        //Englobage de tout les liens pour que l'image soivent centrer verticalement
        $(".carrousel a").wrap('<div class="carrouselimage"></div>');
        //Obliger sinon pas centrer verticalement (Bizarre)
        $(".carrousel div").width(Accueil.model.largeurcotediv);
        //Initialisation de l'attribut mediastorrent-id
        t = -1;
        $.each($(".carrousel a"), function (k, v) {
            $(v).attr("mediastorrent-id", t).addClass("ct");
            t++;
        });
        //Ajout de la classe centre au premier élément de la liste
        //$(".carrousel li:nth-child(1)").width($(window).width()*0.6);
        $(".carrousel li:nth-child(1) div").width(Accueil.model.largeurcentraldiv);
        $(".carrousel img").width(Accueil.model.largeurcentralimg * 0.6);
        $(".carrousel img").css("opacity", 0.5);
        $(".carrousel li:nth-child(1) img").width(Accueil.model.largeurcentralimg).css("opacity", 1);

        // Nombre d'éléments de la liste
        // Ciblage de la bande de diapositives
        $(".carrousel")
            // Application d'une largeur à la bande de diapositive afin de conserver une structrure horizontale
            .css("width", (Accueil.model.largeurcotediv * ($(".carrousel li").length - 1 ) + Accueil.model.largeurcentraldiv))
            .css("height", Accueil.model.largeurcentralimg)
            .css("margin-left", Accueil.model.largeurcotediv + "px");

        $(".carrousel-conteneur")
            // Application de la largeur d'une seule diapositive
            .width(Accueil.model.largeurcotediv * 2 + Accueil.model.largeurcentraldiv)
            // Application de la hauteur d'une seule diapositive
            .height(Accueil.model.largeurcentralimg)
            // Blocage des débordements
            .css("overflow", "hidden").css({"top": $(window).height()/2 - Accueil.model.largeurcentralimg /2+ "px", "position": "relative"});
        Accueil.controller.listener();
        if (Accueil.model.inilistener){

            Accueil.controller.resize();
        }
    },

    listener: function () {
        var o = Accueil;
        $(".ct").mouseover(function (event) {
            event.preventDefault();

            var a = $(this);
            var annimate = false;
            if (a.attr("mediastorrent-id") > o.model.cpt) {
                o.model.cpt++;
                annimate = true;

            } else if (a.attr("mediastorrent-id") < o.model.cpt) {
                o.model.cpt--;
                annimate = true;
            }
            if (annimate){
                $(".carrousel img").animate({
                    opacity: 0

                }, 0);
                $(".carrouselimage").animate({
                    width: o.model.largeurcotediv

                }, 0);
                $(".carrousel li:nth-child(" + (o.model.cpt + 2) + ") div").animate({
                    width: o.model.largeurcentraldiv

                }, 0);
                $(".carrousel li:nth-child(" + (o.model.cpt + 3) + ") img").animate({
                    width: o.model.largeurcentralimg * 0.6,
                    opacity: 0.5
                }, 0);
                $(".carrousel li:nth-child(" + (o.model.cpt + 1) + ") img").animate({
                    width: o.model.largeurcentralimg * 0.6,
                    opacity: 0.5
                }, 0);
                $(".carrousel li:nth-child(" + (o.model.cpt + 2) + ") img").animate({
                    width: o.model.largeurcentralimg,
                    opacity: 1
                }, 0);
                console.info("Cpt " + o.model.cpt);
                $(".carrousel").animate({
                    marginLeft: -(o.model.largeurcotediv * o.model.cpt)
                }, 300);
            }



        });
    },

    resize : function () {
        var o = Accueil;
        $(window).resize(function () {
            o.controller.init();
        });
    }
};