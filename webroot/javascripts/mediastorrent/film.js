/**
 * Version 1.0
 * @author Salorium
 **/
var timer;
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
    $(document).on('mousewheel DOMMouseScroll','.btcontroll', function(e) {
        e.preventDefault();
        delta = e.originalEvent.detail
        if (e.originalEvent.wheelDelta)
            delta = e.originalEvent.wheelDelta*-1;
        console.info(delta);
        if (delta < 0){
            Film.compteur--;
            if (Film.compteur <1)
                Film.compteur = 1;
        }else{
            Film.compteur++;

            if (Film.compteur >= Film.tonObjet.film.length){
                Film.compteur = Film.tonObjet.film.length;
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
			Film.genereControlTopBar(parseInt(a.attr("mediastorrent_id")), false);
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
		Film.init();
	});

});
var Film = {
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
	tonObjet: {
		"base_url": "http://d3gtl9l2a4fn1j.cloudfront.net/t/p/",
		"secure_base_url": "https://d3gtl9l2a4fn1j.cloudfront.net/t/p/",
		"poster_sizes": ["w92", "w154", "w185", "w342", "w500", "original"],
		"backdrop_sizes": ["w300", "w780", "w1280", "original"],
		"film": [
			{
				"poster": "/wnguALxboOSZC793k2YlMB8NbRB.jpg",
				"titre": "Les Schtroumpfs",
				"backdrop": "/ynCi6RF7by2QBvoT1HMRTQEg5KA.jpg"
			},
			{
				"poster": "/9ZQh34mYYpshR0ft1dcMnLGvNQp.jpg",
				"titre": "Les schtroumpfs - les chants de Noel",
				"backdrop": "/mMOQuMFHgHk8Fk8OLAo6noc2eK9.jpg"
			},
			{
				"poster": "/deeWuBjq2JxTMzyws6k7bJY26DS.jpg",
				"titre": "L'Ã¢ge de glace 4 : La dÃ©rive des continents",
				"backdrop": "/tZGKKeWGejt63jiSdi7MTAjCFE9.jpg"
			},
			{
				"poster": "/nqkHXAA43PwDEkQuwWgoXX1qRV5.jpg",
				"titre": "L'Ã¢ge de glace",
				"backdrop": "/cTVhmoHolmeXQOdhMh38i7NnN54.jpg"
			},
			{
				"poster": "/5bIcCP1MYgSIBfTDx9btd0FXx8i.jpg",
				"titre": "L'Ã¢ge de glace 3 - Le temps des dinosaures",
				"backdrop": "/2ZkYXWejAwpkFkNnOY6nQYcJSPn.jpg"
			},
			{
				"poster": "/vFI6o8Xf8U2QdagrKZyEpmbPJf5.jpg",
				"titre": "L'Ã¢ge de glace 2",
				"backdrop": "/vPotRRx8kdDu7CkFovXovgnfiN4.jpg"
			},
			{
				"poster": "/q3kH3gI71GCbWR2aklFOFKkA9Jy.jpg",
				"titre": "L'Ã¢ge de glace fÃªte NoÃ«l",
				"backdrop": "/29jDSAI2aAgmLlOeKTHjSfKyvr5.jpg"
			},
			{
				"poster": "/c7Hw9srT8iaRfokFwwjdCmbj7Da.jpg",
				"titre": "Iron Man",
				"backdrop": "/ZQixhAZx6fH1VNafFXsqa1B8QI.jpg"
			},
			{
				"poster": "/iRrpZZRn4nfFKtkkY8kkbRwH6qE.jpg",
				"titre": "Iron Man 2",
				"backdrop": "/jxdSxqAFrdioKgXwgTs5Qfbazjq.jpg"
			},
			{
				"poster": "/2hH2s3keiXub9kSSKy9kf01YspR.jpg",
				"titre": "L'Homme aux Poings de Fer",
				"backdrop": "/uSZweG7S50fe8vqMHnJHl8H6S33.jpg"
			},
			{
				"poster": "/oTjW5Z72XbNL7ows7sRQLKHPSgT.jpg",
				"titre": "Les Schtroumpfs 2",
				"backdrop": "/oTjW5Z72XbNL7ows7sRQLKHPSgT.jpg"
			},
			/*{
				"poster": "/wCvfrk4wUqwg8py0jJLQkJ3Ta35.jpg",
				"titre": "Bloody Fight in Iron-Rock Valley"
			},*/
			{
				"poster": "/dyKHdcZnuNpyXoKe6bWpxQd3yDY.jpg",
				"titre": "Iron Man 3",
				"backdrop": "/n9X2DKItL3V0yq1q1jrk8z5UAki.jpg"
			},
			{
				"poster": "/uQomOM1emV0uGM50EYg4R9HxlYH.jpg",
				"titre": "Iron Maiden: Flight 666",
				"backdrop": "/jlGF5ZZ1RrXLhQnFLMlFWCz38zB.jpg"
			},/*
			{
				"poster": "/ocH2z9XAEFj5e4aS6SqcUiUW2Fv.jpg",
				"titre": "Aian GÃ¢ru"
			},*/
			{
				"poster": "/qMVlimquAFAMkSFYCK4vRlvZdjI.jpg",
				"titre": "Iron Sky",
				"backdrop": "/mIOWtdsUg9VZXVMwVdO30TejAxn.jpg"
			},
			{
				"poster": "/vkhvQR718AcxaJMMuADQki2Pm1I.jpg",
				"titre": "Le sang des templiers",
				"backdrop": "/hxGSxNREU4xbA1kfiJRvnPPoL29.jpg"
			},/*
			{
				"titre": "Iron and Beyond"
			},*/
			{
				"poster": "/9NGEclCMLajEXEv6oxq9k2Da2c2.jpg",
				"titre": "Sur la piste du Marsupilami",
				"backdrop": "/pHZS0Gf9bqZcyJMYgU0Fc2y6qE4.jpg"
			},
			{
				"poster": "/niNI2RxXDqrHGdM5sADHIyH09Y6.jpg",
				"titre": "Taken",
				"backdrop": "/d5vwBiuJI1a2hBcGjhsWhpmAkL7.jpg"
			},/*
			{
				"poster": "/oHYLVJ8ivmG05ENPhnU4kELy6Lj.jpg",
				"titre": "Taken"
			},*/
			{
				"poster": "/8bpWdsgC8YLzyJdPsAySGxBxkM7.jpg",
				"titre": "Taken 2",
				"backdrop": "/uLQjXZq6307jTgcACnhOeh5mCK2.jpg"
			},
			{
				"poster": "/16o4fitcQafAWsTkXlu9ENdWd1Y.jpg",
				"titre": "Stephen Grant: Taken for Granted",
				"backdrop": "/aOcawrAyQz1tHf6jXzawlf3pIZs.jpg"
			},/*
			{
				"poster": "/5cAtn4HbDA117nHKWwzBF3HoZjk.jpg",
				"titre": "NÃ´kÃ´ furin: torareta onna"
			},*/
			{
				"poster": "/l054o7fLS49Z7febJ4WLpWoPOzh.jpg",
				"titre": "Mon fils a disparu",
				"backdrop": "/f2mj0tccSmSja6C8vt1HGpd1Wgm.jpg"
			},/*
			{
				"titre": "Seontaek"
			},
			{
				"poster": "/pyGXYUEyNE6mYutjc5KMtJkexLj.jpg",
				"titre": "Catch The Hold Not Taken"
			},*/
			{
				"poster": "/Are4nyUaGNgOLVsGdn0BI4MwCge.jpg",
				"titre": "Taken for a Ride",
				"backdrop": "/fO6ZM7RssT22vGZkgqruQyUHR8V.jpg"
			},/*
			{
				"titre": "Over Taken"
			},*/
			{
				"poster": "/1Bn8x8unTKnysriOkqHCzge5Cg3.jpg",
				"titre": "Le Courage au coeur",
				"backdrop": "/mHzAksI6qo7PVDZXTgbmRpLPujw.jpg"
			},/*
			{
				"poster": "/lNu4qDoQaOLp4nkA5oqrzu6yyQj.jpg",
				"titre": "They've Taken Our Children: The Chowchilla Kidnapping"
			},
			{
				"titre": "Military Camp at Tampa, Taken from Train"
			},
			{
				"titre": "The Old Maid Having Her Picture Taken"
			},
			{
				"titre": "President Coolidge, Taken on the White House Grounds"
			},
			{
				"titre": "Taken Back: Finding Haley"
			},*/
			{
				"poster": "/iwXm4Qq9qbvd59cqmqYJW2ZS8lB.jpg",
				"titre": "Six Jours Sept Nuits",
				"backdrop": "/yeK1MPP6wDAnf4wATPqRfMvXHSS.jpg"
			},
			{
				"poster": "/pTqNNlU8QAW4iX8w14icBtp3bV4.jpg",
				"titre": "40 jours et 40 nuits",
				"backdrop": "/v8qppTQ6cK8rIInke51WDi1rRM9.jpg"
			}


		]
	},
	compteur: 0,
	container : null,
	nombreControlDansLargeur: 0,
	borneControlPartieGauche: 0,
	borneControlPartieCentral: 0,
	containerControl: null,
	containerBtControl: null,
	containerCss: null,
	containerBtG: null,
	containerBtD: null,
	containerBande: null,
    containerDetailsFilm : null,
	CssModulable: "",
	zindex: 1,
	init : function(){
		 if (this.container){
			 $(this.container).remove();
		 }
		if ($(window).height() < 750 || $(window).width() < 960){
			console.info("Mosaique");
		}else{
			this.initTopBar();
		}
	},
	initTopBar: function () {
		this.container = $('<div></div>').appendTo(".container");
        this.container.append('<div style="height: 1px;"></div>')
        this.containerDetailsFilm = $('<div class="detailsFilm">Lorem ipsum dolor sit amet, consectetur adipisicing elit. A, accusamus aliquam autem enim error et eum id itaque molestias natus placeat, quod quos ratione reprehenderit repudiandae, sed sint sunt veritatis.</div>').appendTo(this.container);
        this.containerDetailsFilm.hide();
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
        $(".container").css("height",this.hauteurWindows);
		console.info(this.hauteurWindows);
		this.largeurWindows = $(".container").width();
		this.hauteurControl = Math.round(this.percentageHauteurControl * this.hauteurWindows / 100);
		this.largeurControl = Math.round(this.hauteurControl * this.largeurReferenceControl / this.hauteurReferenceControl);
		id = 0;
		ok = false;
		while (id < this.tonObjet.poster_sizes.length && !ok) {
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
		if (id >= this.tonObjet.backdrop_sizes.length) id--;
		this.qualitebackdrop = this.tonObjet.backdrop_sizes[id];
		this.demiLargeurControl = Math.round(this.largeurControl / 2);
		this.hauteurRefletControl = Math.round(this.hauteurControl / 4);
		this.topControl = this.hauteurWindows - this.hauteurControl - this.hauteurRefletControl;
		this.pixelArrondi = Math.round(this.largeurControl * this.percentageArrondi / 100);
		this.nombreControlDansLargeur = Math.floor((this.largeurWindows - 200 - this.largeurControl) / this.demiLargeurControl) + 1;
		if (this.tonObjet.film.length <= this.nombreControlDansLargeur) this.nombreControlDansLargeur = this.tonObjet.film.length;
		this.borneControlPartieGauche = Math.floor(this.nombreControlDansLargeur / 2);
		this.borneControlPartieCentral = this.borneControlPartieGauche + 1;
		this.containerCss.append(this.genereCssRotationTopBar());
        this.containerDetailsFilm.css({
            "height" :this.topControl-80+"px"
        });

        this.containerControl.css({
			"top": 15 + "px",
			"height": (this.hauteurControl + this.hauteurRefletControl) + "px"
		});
        this.qualitebackdrop = "w780";
		$("#rec1").attr("height", this.hauteurRefletControl);
		this.containerControl.css({
			"width": ((this.nombreControlDansLargeur - 1) * this.demiLargeurControl + 200 + this.largeurControl) + "px"
		});
		/*this.containerBande.css({
			//"height": (this.hauteurRefletControl * 2) + "px"
		});*/
        this.containerBtControl.css({
            "width":  ((this.nombreControlDansLargeur - 1) * this.demiLargeurControl + this.largeurControl) + "px"
        })
		this.genereControlTopBar(null, true);
	},

	genereControlTopBar: function (milieux, screenshot) {
		var central = this.borneControlPartieCentral;
		if (milieux != null) {
			if (0 <= milieux && milieux <= this.tonObjet.film.length) {
				central = milieux;

			} else {
				this.genereControlTopBar(null,true);
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
		if (btd > this.tonObjet.film.length) {
			btd = this.tonObjet.film.length;
		}
		this.containerBtG.attr("mediastorrent_id", btg);
		this.containerBtD.attr("mediastorrent_id", btd);
		var o = this;
		id = central - this.borneControlPartieCentral;
		if (central - this.borneControlPartieCentral < 0) id = 0;
		max = id + this.nombreControlDansLargeur;
		if (max > this.tonObjet.film.length) max = this.tonObjet.film.length;
		if (max - id < this.nombreControlDansLargeur) {
			id = max - this.nombreControlDansLargeur;
		}
		console.info("Nombre controle dans la largeur : " + this.nombreControlDansLargeur + " Nb film " + this.tonObjet.film.length + " id " + id + " max " + max);
		while (id < max) {
			this.afficheControlTopBar(this.tonObjet.film[id], id + 1, central, screenshot);
			id++;
		}
		this.containerCss.append(this.CssModulable);
	},
	afficheControlTopBar: function (control, id, centre, screenshot) {
		if (id <= centre - 1) {
			if (control.poster) {
				this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.demiLargeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="' + this.tonObjet.secure_base_url + this.qualiteposter + control.poster + '" alt="' + control.titre + '"></a></div> </div></div>');
			} else {
				this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.demiLargeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="proxy-image.php?titre=' + encodeURIComponent(control.titre) + '" alt="' + control.titre + '"></a></div> </div></div>');
			}
			this.zindex++;
			this.addUniqueCssGaucheTopBar(id);
		} else if (id <= centre) {
			if (control.poster) {
				this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.largeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot2"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="' + this.tonObjet.secure_base_url + this.qualiteposter + control.poster + '" alt="' + control.titre + '"></a></div> </div></div>');
			} else {
				this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.largeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot2"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="proxy-image.php?titre=' + encodeURIComponent(control.titre) + '" alt="' + control.titre + '"></a></div> </div></div>');
			}
			if (control.backdrop && screenshot) {
				$(".container").css({
					"background": 'url("' + this.tonObjet.secure_base_url + this.qualitebackdrop + control.backdrop + '") center center fixed',
					"background-size": "cover"
				});
                console.log($('html'));
			} else {
				$(".container").css("background", 'url("http://mediastorrent/images/fondEcran/black_hole_scene-1920x1080.jpg") no-repeat center center fixed');
			}
			this.zindex--;
            this.afficheDetailsFilm(id-1);
			this.addUniqueCssGaucheTopBar(id);
		} else {
			if (control.poster) {
				this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.demiLargeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot1"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="' + this.tonObjet.secure_base_url + this.qualiteposter + control.poster + '" alt="' + control.titre + '"></a></div> </div></div>');
			} else {
				this.containerBtControl.append('<div class="scene3D" style="z-index :' + this.zindex + '; width:' + (this.demiLargeurControl) + 'px; height:' + this.hauteurControl + 'px;"><div class="rot1"><div id="controlbt' + id + '" class="round"><a class="btcontroll" mediastorrent_id="' + id + '"> <img style="width: ' + this.largeurControl + 'px; height: ' + this.hauteurControl + 'px; border-radius: ' + this.pixelArrondi + 'px; -webkit-border-radius: ' + this.pixelArrondi + 'px; -moz-border-radius: ' + this.pixelArrondi + 'px; -ms-border-radius: ' + this.pixelArrondi + 'px;" src="proxy-image.php?titre=' + encodeURIComponent(control.titre) + '" alt="' + control.titre + '"></a></div> </div></div>');

			}
			this.zindex--;
			this.addUniqueCssDroiteTopBar(id);

		}

	} ,
    afficheDetailsFilm : function (id){
         this.containerDetailsFilm.show();
    }
}