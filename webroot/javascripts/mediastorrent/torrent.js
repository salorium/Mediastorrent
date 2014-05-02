/**
 * Created with JetBrains PhpStorm.
 * User: Salorium
 * Date: 04/10/13
 * Time: 04:52
 * To change this template use File | Settings | File Templates.
 */

var Torrent = {

    resized: false,
    liste: [],
    listeoriginal: [],
    listorrent: null,
    cpt: 0,
    nbtorrents: 0,
    listeselectionnee: [],
    listeselectionneeid: -1,
    baseurl: "",
    click: 0,
    user: null,
    keyconnexion: null,
    changedurl: false,
    sortcolonne: -1,
    sorttype: 1,
    dStatus: { started: 1, paused: 2, checking: 4, hashing: 8, error: 16 },
    resetCpt: function () {
        this.changedurl = true;
        this.baseurl = $("#seedboxhost").val();
        $("#seedboxhost").attr('disabled', 'disabled');
    },
    init: function (seedbox, user, keyconnexion) {
        var o = this;
        this.user = user;
        this.keyconnexion = keyconnexion;
        $(".container").empty();
        $(".container").html('<div id="conteneurtorrents" class="conteneurtorrents"><div style="text-align: center;"><table id="store" class="infosrtorrent"><tr><td><select id="seedboxhost" onchange="Torrent.resetCpt();" style="width: 200px;margin: 0;"></select></td>' +
            '<td>|</td><td><img title="Disque dur" src="images/disk.svg"></td><td><progress id="storage" style="width: 150px;" class="diskspace" value="500" max="1000"></progress></td>' +
            '<td>|</td><td><img src="images/upload.svg"></td><td>Vitesse <span id="vup"></span> Limite <span id="vupl"></span> Total <span id="vupt"></span></td>' +
            '<td>|</td><td><img src="images/download.svg"></td><td>Vitesse <span id="vdl"></span> Limite <span id="vdll"></span> Total <span id="vdlt"></span></td>' +
            '</tr></table></div>' +
            '<div class="moitietorrent">' +
            '<div style="text-align: center;"><table id="store" class="infosrtorrent"><tr>' +
            '<td>Tri :</td>' +
            '<td><button sort-colonne="0" class="round ordonnetorrent" style="margin: 0;">Status <span></span></button></td>' +
            '<td>|</td><td><button sort-colonne="1" class="round ordonnetorrent" style="margin: 0;">Nom <span></span></button></td>' +
            '<td>|</td><td><button sort-colonne="25" class="round ordonnetorrent" style="margin: 0;">Date d\'ajout <span></span></button></td>' +
            '</tr></table></div>' +
            '<div id="listorrent" ></div>' +
            '</div>' +
            '<div class="moitietorrent">' +
            '<fieldset id="detailstorrent" >' +
            '<legend><span style="text-decoration: line-through;">Infos</span><span style="">Fichier</span></legend>' +
            'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur corporis dolor, dolorum enim, eos incidunt, ipsum iste itaque labore molestias nam neque officia optio quidem rem sunt tempora tempore veniam.' +
            '</fieldset>' +
            '</div></div>' +
            '<div style="text-align: center;"><a href="#ADD"><img width="40px"  title="Ajouter un Torrent" src="images/world.svg"/></a><a href="#ADD"><img width="40px"  title="Démarrer un Torrent" src="images/play.svg"/></a><a href="#ADD"><img width="40px"  title="Mettre en pause un Torrent" src="images/pause.svg"/></a><a href="#ADD"><img width="40px"  title="Arrêter un Torrent" src="images/stop.svg"/></a></div>');
        /* $.each(seedbox, function(k,v){
         $("#seedboxhost").append('<option value="'+v[0]+'">'+v[1]+"</option>")
         });
         /*this.listenerordonnetorrent();
         this.listorrent = $("#listorrent");
         this.listorrent.append(this.templatelisttorrent());
         $("#conteneurtorrents").height($(window).height()-120);
         console.log(($("#conteneurtorrents").height()-(19*2+56*2))/$("fieldset.torrent").height());
         this.nbtorrents= Math.floor(($("#conteneurtorrents").height()-(19*2+56*2))/$("fieldset.torrent").height());
         this.listorrent.empty();
         if (!this.resized){
         this.resized =true;
         this.resize(seedbox);
         this.baseurl = seedbox[0][0];
         this.resetCpt();
         setTimeout(function(){
         Torrent.update("");
         },1);

         }*/

    },
    getIconDessin: function (name) {
        switch (name) {
            case "checking":
                return 'M409.338,216.254c-10.416-54.961-58.666-95.777-115.781-95.777c-35.098,0-67.631,15.285-89.871,41.584' +
                    'c-37.148-9.906-76.079,11.781-86.933,48.779C78.16,222.176,50.6,257.895,50.6,299.303c0,50.852,41.37,92.221,93.222,92.221H369.18' +
                    'c50.85,0,92.221-41.369,92.221-92.221C461.4,263.389,440.941,231.457,409.338,216.254z M369.18,351.523H143.821' +
                    'c-29.795,0-53.222-23.426-53.222-52.221c0-34.078,27.65-60.078,62.186-53.816c-11.536-39.596,44.131-61.93,64.641-32.348' +
                    'c5.157-14.582,25.823-52.662,76.131-52.662c38.027,0,77.361,26.08,78.664,84.982c25.363,0.098,49.18,18.432,49.18,53.844' +
                    'C421.4,328.098,397.975,351.523,369.18,351.523z M193.019,273.39h15.715c0.458-31.007,25.821-56.092,56.933-56.092' +
                    'c13.515,0,26.622,4.829,36.909,13.6l0.705,0.6l-13.956,13.956l-0.597-0.472c-6.635-5.236-14.608-8.006-23.062-8.006' +
                    'c-20.259,0-36.794,16.258-37.25,36.414h16.007l-25.715,25.706L193.019,273.39z M321.952,273.142' +
                    'c-0.458,31.009-25.821,56.094-56.933,56.094c-13.523,0-26.64-4.837-36.931-13.618l-0.704-0.601l13.955-13.954l0.597,0.472' +
                    'c6.639,5.248,14.62,8.021,23.083,8.021c20.26,0,36.794-16.258,37.25-36.414h-16.006l25.715-25.705l25.688,25.705H321.952z';
                break;
            case "queue":
                return 'M409.338,216.254c-10.416-54.961-58.666-95.777-115.781-95.777c-35.098,0-67.631,15.285-89.871,41.584' +
                    'c-37.148-9.906-76.079,11.781-86.933,48.779C78.16,222.176,50.6,257.895,50.6,299.303c0,50.852,41.37,92.221,93.222,92.221H369.18' +
                    'c50.85,0,92.221-41.369,92.221-92.221C461.4,263.389,440.941,231.457,409.338,216.254z M369.18,351.523H143.821' +
                    'c-29.795,0-53.222-23.426-53.222-52.221c0-34.078,27.65-60.078,62.186-53.816c-11.536-39.596,44.131-61.93,64.641-32.348' +
                    'c5.158-14.582,25.824-52.662,76.131-52.662c38.027,0,77.361,26.08,78.664,84.982c25.363,0.098,49.18,18.432,49.18,53.844' +
                    'C421.4,328.098,397.975,351.523,369.18,351.523z M264.756,333.012c-28.891,0-52.678-21.721-55.678-50.046l16.461-3.281' +
                    'c1.406,20.414,18.477,36.628,39.217,36.628c21.695,0,39.348-17.649,39.348-39.347c0-21.696-17.652-39.347-39.348-39.347' +
                    'c-9.793,0-19.569,3.517-26.661,9.503l10.837,10.838l-41.046,7.493l7.493-41.047l10.883,10.883' +
                    'c10.158-9.034,24.356-14.368,38.494-14.368c30.904,0,56.047,25.142,56.047,56.045C320.803,307.87,295.66,333.012,264.756,333.012z' +
                    'M286.135,276.753v12.423h-30.397v-32.644h12.423v20.221H286.135z';
                break;
            case 'pause':
                return 'm 409.338,216.254 c -10.416,-54.961 -58.666,-95.777 -115.781,-95.777 -35.098,0 -67.631,15.285 -89.871,41.584 -37.148,-9.906 -76.079,11.781 -86.933,48.779 C 78.16,222.176 50.6,257.895 50.6,299.303 c 0,50.852 41.37,92.221 93.222,92.221 l 225.358,0 c 50.85,0 92.221,-41.369 92.221,-92.221 -0.001,-35.914 -20.46,-67.846 -52.063,-83.049 z m -40.158,135.269 -225.359,0 c -29.795,0 -53.222,-23.426 -53.222,-52.221 0,-34.078 27.65,-60.078 62.186,-53.816 -11.536,-39.596 44.131,-61.93 64.641,-32.348 5.157,-14.582 25.823,-52.662 76.131,-52.662 38.027,0 77.361,26.08 78.664,84.982 25.363,0.098 49.18,18.432 49.18,53.844 -0.001,28.796 -23.426,52.221 -52.221,52.221 z m -117.40686,-126.225 0,107.05664 -43.27771,0 0,-107.05664 z m 28.69177,107.05664 0,-107.05664 43.03766,0 0,107.05664 z';
                break;
            case 'ul':
                return 'm 409.338,216.254 c -10.416,-54.961 -58.666,-95.777 -115.781,-95.777 -35.098,0 -67.631,15.285 -89.871,41.584 -37.148,-9.906 -76.079,11.781 -86.933,48.779 C 78.16,222.176 50.6,257.895 50.6,299.303 c 0,50.852 41.37,92.221 93.222,92.221 l 225.358,0 c 50.85,0 92.221,-41.369 92.221,-92.221 -0.001,-35.914 -20.46,-67.846 -52.063,-83.049 z m -40.158,135.269 -225.359,0 c -29.795,0 -53.222,-23.426 -53.222,-52.221 0,-34.078 27.65,-60.078 62.186,-53.816 -11.536,-39.596 44.131,-61.93 64.641,-32.348 5.157,-14.582 25.823,-52.662 76.131,-52.662 38.027,0 77.361,26.08 78.664,84.982 25.363,0.098 49.18,18.432 49.18,53.844 -0.001,28.796 -23.426,52.221 -52.221,52.221 z m -133.90703,-77.57749 -28.75689,0.11871 59.23404,-59.82071 59.72327,59.32967 -28.75602,0.1187 0.25445,61.64094 -61.44441,0.25364 z';
                break;
            case 'dl':
                return 'm 409.338,216.254 c -10.416,-54.961 -58.666,-95.777 -115.781,-95.777 -35.098,0 -67.631,15.285 -89.871,41.584 -37.148,-9.906 -76.079,11.781 -86.933,48.779 C 78.16,222.176 50.6,257.895 50.6,299.303 c 0,50.852 41.37,92.221 93.222,92.221 l 225.358,0 c 50.85,0 92.221,-41.369 92.221,-92.221 -0.001,-35.914 -20.46,-67.846 -52.063,-83.049 z m -40.158,135.269 -225.359,0 c -29.795,0 -53.222,-23.426 -53.222,-52.221 0,-34.078 27.65,-60.078 62.186,-53.816 -11.536,-39.596 44.131,-61.93 64.641,-32.348 5.157,-14.582 25.823,-52.662 76.131,-52.662 38.027,0 77.361,26.08 78.664,84.982 25.363,0.098 49.18,18.432 49.18,53.844 -0.001,28.796 -23.426,52.221 -52.221,52.221 z m -72.44351,-67.76373 28.75617,-0.23586 -58.98986,60.06152 -59.96447,-59.08589 28.7553,-0.23584 -0.50555,-61.63939 61.44286,-0.50394 z';
                break;
            case 'error':
                return 'm 406.104,227.64 c -4.444,-55.967 -51.26801,-100 -108.37601,-100 -38.81699,0 -72.87799,20.34801 -92.11398,50.953 -36.18201,-20.457 -82.326,3.61099 -85.21701,45.89799 C 80.72899,229.34898 50,263.13198 50,304.11401 c 0,44.31799 35.928,80.246 80.246,80.246 l 251.508,0 c 44.31799,0 80.246,-50.92801 80.246,-80.246 0,-35.828 -23.48401,-66.16202 -55.896,-76.47401 z m -175.37135,-1.71023 35.26464,35.25897 35.25473,-35.26322 21.96958,21.95258 -35.26465,35.26464 35.26748,35.26323 -21.95683,21.96391 -35.27031,-35.27031 -35.27031,35.27598 -21.94975,-21.96392 35.26465,-35.26747 -35.27031,-35.26465 z';
                break;
            case 'terminer':
                return 'M409.338,216.254c-10.416-54.961-58.666-95.777-115.781-95.777c-35.098,0-67.631,15.285-89.871,41.584' +
                    'c-37.148-9.906-76.079,11.781-86.933,48.779C78.16,222.176,50.6,257.895,50.6,299.303c0,50.852,41.37,92.221,93.222,92.221H369.18' +
                    'c50.85,0,92.221-41.369,92.221-92.221C461.4,263.389,440.941,231.457,409.338,216.254z M369.18,351.523H143.821' +
                    'c-29.795,0-53.222-23.426-53.222-52.221c0-34.078,27.65-60.078,62.186-53.816c-11.536-39.596,44.131-61.93,64.641-32.348' +
                    'c5.157-14.582,25.823-52.662,76.131-52.662c38.027,0,77.361,26.08,78.664,84.982c25.363,0.098,49.18,18.432,49.18,53.844' +
                    'C421.4,328.098,397.975,351.523,369.18,351.523z M248.105,322.5c-19.184-22.593-31.987-36.996-55.547-58.833l11.78-10.892' +
                    'c13.781,6.668,22.52,11.741,38.008,22.782c29.118-33.043,48.358-49.807,84.121-72.058l3.835,8.816' +
                    'C300.81,238.055,279.209,266.722,248.105,322.5z';

                break;
            case "noncomplet":
                return 'M409.338,166.521c-10.416-54.961-58.666-95.777-115.781-95.777c-35.098,0-67.631,15.285-89.871,41.584' +
                    'c-37.148-9.906-76.079,11.781-86.933,48.779C78.16,172.442,50.6,208.161,50.6,249.569c0,50.852,41.37,92.221,93.222,92.221H369.18' +
                    'c50.85,0,92.221-41.369,92.221-92.221C461.4,213.655,440.941,181.724,409.338,166.521z M369.18,301.79H143.821' +
                    'c-29.795,0-53.222-23.426-53.222-52.221c0-34.078,27.65-60.078,62.186-53.816c-11.536-39.596,44.131-61.93,64.641-32.348' +
                    'c5.157-14.582,25.823-52.662,76.131-52.662c38.027,0,77.361,26.08,78.664,84.982c25.363,0.098,49.18,18.432,49.18,53.844' +
                    'C421.4,278.364,397.975,301.79,369.18,301.79z M278.591,363.455h-45.182v37.802h-33.888v0.463v39.537h112.957V401.72v-0.463h-33.888' +
                    'V363.455z M414.7,401.257h-79.631v40H414.7V401.257z M176.931,401.257H97.3v40h79.631V401.257z';
                break;
        }
    },
    analiseState: function (state, done) {
        status = "";
        icon = null;
        if (state & this.dStatus.checking) {
            icon = {name: "checking", color: "orange", colorname: "checking"};
            status = "Vérification";
        } else if (state & this.dStatus.hashing) {
            icon = {name: "queue", color: "purple", colorname: "queue"};
            status = "Mis dans la file";
        } else {
            if (state & this.dStatus.started) {
                if (state & this.dStatus.paused) {
                    icon = {name: "pause", color: "gold", colorname: "pause"};
                    status = "Pause";
                } else {
                    icon = (done == 1000) ? {name: "ul", color: "#18C72F", colorname: "ul"} : {name: "dl", color: "#1832C7", colorname: "dl"};
                    status = (done == 1000) ? "Envoi" : "Téléchargement";
                }

            }
        }
        if ((done < 1000) && status === "") {
            icon = {name: "noncomplet", color: "gray", colorname: "noncomplet"};
            status = "Arrêté";
        }
        if (state & this.dStatus.error) {
            if (icon == null) {
                icon = {name: "error", color: "#FF0000", colorname: "error"};
            } else {
                icon.color = "#FF0000";
                icon.colorname = "error";
            }
        }
        if (done == 1000 && status == "") {
            icon = {name: "terminer", color: "mediumseagreen", colorname: "terminer"};
            status = "Terminé";
        }
        return [status, icon];
    },
    templatelisttorrent: function (torrent, cpt) {
        if (torrent == null)
            torrent = [];
        if (torrent.length == 0) {
            state = this.analiseState(1, 100);
        } else {
            state = this.analiseState(torrent[0], torrent[3]);
        }
        return '<fieldset id="' + torrent[27] + '" class="torrent ' + ($.inArray(torrent[27], this.listeselectionnee) > -1 ? "torrentselect" : "") + '" idcpt="' + cpt + '"><legend><table><tr><td><svg title="' + state[0] + '" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="60px" height="60px"  viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">' +
            '<path style="fill: ' + state[1].color + ';" d="' + this.getIconDessin(state[1].name) + '"/></svg></td><td>' + (torrent[1] ? torrent[1] : "Test" ) + '</td></tr></table></legend>' +
            '<table style="width: 100%"><tr><td width="100%"><progress class="' + state[1].colorname + '" id="p" value="' + (torrent[3] ? torrent[3] / 10 : "0" ) + '" max="100"></progress></td><td  width="70px" style="text-align:center;min-width:70px; "><span class="pcr">' + (torrent[3] ? torrent[3] / 10 : "0" ) + '</span>%</td></tr></table>' +
            '<table style="width: 100%">' +
            '<tr><td width="80px;" style="vertical-align: bottom;">Ajouté</td><td width="170px">: ' + ((torrent[25] > 3600 * 24 * 365) ? theConverter.date(iv(torrent[25])) : "") + '</td><td width="60px;">Seedtime</td><td>: ' + ((torrent[24] > 3600 * 24 * 365) ? theConverter.time(new Date().getTime() / 1000 - (iv(torrent[24])), true) : "") + '</td><td align="right">Ratio : ' + torrent[6] / 1000 + '</td></tr>' +
            '<tr><td style="vertical-align: bottom;">Sources</td><td>: ' + (torrent[13] ? torrent[13] : "0" ) + '(' + (torrent[11] ? torrent[11] : "0" ) + ')</td><td>Clients</td><td>: ' + (torrent[12] ? torrent[12] : "0" ) + '(' + (torrent[10] ? torrent[10] : "0" ) + ')</td><td align="right">Upload : ' + (theConverter.speed(torrent[7]) != "" ? theConverter.speed(torrent[7]) : "-") + ' Download : ' + (theConverter.speed(torrent[8]) != "" ? theConverter.speed(torrent[8]) : "-") + '</td></tr>' +
            '<tr><td style="vertical-align: bottom;">Télécharger</td><td >: ' + (theConverter.bytes(torrent[4], 2) != "" ? theConverter.bytes(torrent[4], 2) : "-" ) + '/' + (theConverter.bytes(torrent[2], 2) != "" ? theConverter.bytes(torrent[2], 2) : "-" ) + '</td><td>Envoyé</td><td>: ' + (theConverter.bytes(torrent[5], 2) != "" ? theConverter.bytes(torrent[5], 2) : "-" ) + '</td><td align="right">Temps restant : ' + (torrent[9] != -1 ? theConverter.time(torrent[9]) : "∞") + '</td></tr>' +
            '</table></fieldset>';
    },
    afficheTorrent: function () {
        this.listorrent.empty();
        if (this.cpt > 0) {
            this.listorrent.append("<span style='display: block;text-align: center;padding: 2px;' onclick='Torrent.next(" + (this.cpt - 1) + ")'>▲</span>");
        }
        var max = this.cpt + this.nbtorrents;
        if (this.cpt + this.nbtorrents > this.liste.length)
            max = this.liste.length;
        for (i = this.cpt; i < max; i++) {
            this.listorrent.append(this.templatelisttorrent(this.liste[i], i));
        }
        $('fieldset.torrent').bind('mousewheel DOMMouseScroll', function (e) {
            e.preventDefault();
            delta = e.detail
            if (e.wheelDelta)
                delta = e.wheelDelta * -1;
            if (delta < 0) {
                Torrent.cpt--;
                if (Torrent.cpt < 0)
                    Torrent.cpt = 0;
            } else {
                Torrent.cpt++;

                if (Torrent.cpt + Torrent.nbtorrents > Torrent.liste.length) {
                    Torrent.cpt = Torrent.liste.length - Torrent.nbtorrents;
                }
                if (Torrent.cpt < 0)
                    Torrent.cpt = 0;
            }
            Torrent.afficheTorrent();
        });
        $('fieldset.torrent').bind('click', function (e) {
            e.preventDefault();

            if (e.shiftKey) {
                Torrent.listeselectionnee = [];
                $(".torrent").removeClass("torrentselect");
                if (Torrent.listeselectionneeid > -1) {
                    max = $(e.currentTarget).attr("idcpt");
                    i1 = Torrent.listeselectionneeid;
                    if (i1 < max) {
                        for (i = i1; i < max; i++) {

                            Torrent.listeselectionnee.push(Torrent.liste[i][27]);
                            $("#" + Torrent.liste[i][27]).addClass("torrentselect");
                        }
                    } else {
                        for (i = i1; i > max; i--) {
                            Torrent.listeselectionnee.push(Torrent.liste[i][27]);
                            $("#" + Torrent.liste[i][27]).addClass("torrentselect");
                        }
                    }
                    Torrent.listeselectionnee.push($(e.currentTarget).attr("id"));
                    $(e.currentTarget).addClass("torrentselect");
                } else {
                    Torrent.listeselectionnee.push($(e.currentTarget).attr("id"));
                    Torrent.listeselectionneeid = ($(e.currentTarget).attr("idcpt"));
                }
                //  mon action
            } else if (e.ctrlKey) {
                id = $.inArray($(e.currentTarget).attr("id"), Torrent.listeselectionnee)
                if (id > -1) {
                    $(e.currentTarget).removeClass("torrentselect");
                    Torrent.listeselectionnee.splice(id, 1);
                } else {
                    Torrent.listeselectionnee.push($(e.currentTarget).attr("id"));
                    $(e.currentTarget).addClass("torrentselect");
                }
                Torrent.listeselectionneeid = ($(e.currentTarget).attr("idcpt"));
            } else {
                Torrent.listeselectionnee = [];
                Torrent.listeselectionnee.push($(e.currentTarget).attr("id"));
                Torrent.listeselectionneeid = ($(e.currentTarget).attr("idcpt"));
                $(".torrent").removeClass("torrentselect");
                $(e.currentTarget).addClass("torrentselect");

            }
        });
        if (this.liste.length > this.nbtorrents) {
            var max = this.cpt + 1;
            if (max + this.nbtorrents - 1 < this.liste.length)
            //max = this.liste.length-3;
                this.listorrent.append("<span style='display: block;text-align: center;padding: 2px;' onclick='Torrent.next(" + max + ")'>▼</span>");
        }
    },
    update: function (cid) {
        $.ajax({
            url: "http://" + Torrent.baseurl + '/index2.php?controller=Torrent&fonction=liste&cid=' + cid + "&login=" + Torrent.user + "&keyconnexion=" + Torrent.keyconnexion,
            dataType: "json",
            //contentType: "application/json",
            success: function (response, textStatus, jqXHR) {
                Debugger.updateDebugger(response);
                console.info(response.showdebugger === 0);
                if (response.showdebugger === 0) {

                    response = response.torrent;
                    if (response[2] == Torrent.baseurl) {
                        Torrent.listorrent.empty();
                        Torrent.conversionListe(response[0]);
                        /*if (response != null){
                         Torrent.afficheTorrent();
                         }*/
                        $("#storage").attr({"value": response[4], "max": response[3], "title": theConverter.bytes(response[4], 2) + " / " + theConverter.bytes(response[3], 2)});
                        $("#vup").html((response[5][0] == 0 ? "" : theConverter.speed(response[5][0])));
                        $("#vupl").html((response[5][1] == 0 ? "Non" : theConverter.speed(response[5][1])));
                        $("#vupt").html(theConverter.bytes(response[5][2], 2));

                        $("#vdl").html((response[5][3] == 0 ? "" : theConverter.speed(response[5][3])));
                        $("#vdll").html((response[5][4] == 0 ? "Non" : theConverter.speed(response[5][4])));
                        $("#vdlt").html(theConverter.bytes(response[5][5], 2));


                        setTimeout(function () {
                            Torrent.update(response[1]);
                        }, 2000);
                    } else {
                        setTimeout(function () {
                            Torrent.update("");
                        }, 1);

                    }
                } else {
                    Torrent.listorrent.empty();
                    Torrent.listorrent.html('<div> <span class="error">Impossible de se connecter à rtorrent :(</span></div>');
                    setTimeout(function () {
                        Torrent.update(response[1]);
                    }, 2000);
                }
                //Torrent.parse();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(jqXHR.responseText + " " + textStatus);
                setTimeout(function () {
                    Torrent.update(cid);
                }, 1);
            }
        });
    },

    conversionListe: function (liste) {
        if (liste != null) {
            if (this.listeoriginal.length == 0 || this.changedurl) {
                this.listeoriginal = liste;
                this.cpt = 0;
                this.changedurl = false;
                $("#seedboxhost").removeAttr('disabled');
            } else {
                $.each(liste, function (k, v) {
                    if (v == false) {
                        delete Torrent.listeoriginal[k];
                    } else {
                        if (Torrent.listeoriginal[k]) {
                            $.each(v, function (kk, vv) {
                                Torrent.listeoriginal[k][kk] = vv;
                            });
                        } else {
                            Torrent.listeoriginal[k] = v;
                        }
                    }
                });
            }

            this.liste = [];
            $.each(this.listeoriginal, function (k, v) {
                Torrent.liste[Torrent.liste.length] = v;
            });
            //Tri par fusion si nécessaire
            if (this.sortcolonne > -1) {
                this.liste = triFusion.triFusion(this.liste, this.sortcolonne, this.sorttype);
            }

            Torrent.afficheTorrent();
        }
    },

    next: function (id) {
        this.cpt = id;
        this.afficheTorrent();
    },
    resize: function (seedbox) {
        var o = this;
        $(window).resize(function () {
            o.init(seedbox, o.user, o.keyconnexion);
            o.afficheTorrent();
        });
    },

    listenerordonnetorrent: function () {
        $('button.ordonnetorrent').bind('click', function (e) {
            e.preventDefault();
            if ($("button.anc").length > 0 && $("button.anc").attr("sort-colonne") != $(e.currentTarget).attr("sort-colonne")) {
                $("button.anc").children().html("");
                $("button.anc").removeAttr("sort-type");
                $("button.anc").removeClass("anc");
            }
            if ($(e.currentTarget).attr("sort-type") == null) {
                $(e.currentTarget).attr("sort-type", 1);
                $(e.currentTarget).children().html("▲");
            } else if ($(e.currentTarget).attr("sort-type") == 1) {
                $(e.currentTarget).attr("sort-type", -1);
                $(e.currentTarget).children().html("▼");
            } else {
                $(e.currentTarget).attr("sort-type", 1);
                $(e.currentTarget).children().html("▲");
            }
            $(e.currentTarget).addClass("anc");
            Torrent.sortcolonne = $(e.currentTarget).attr("sort-colonne");
            Torrent.sorttype = $(e.currentTarget).attr("sort-type");
            Torrent.afficheTorrent();
        });
    }


}