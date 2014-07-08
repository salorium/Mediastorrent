/**
 * Created by salorium on 15/03/14.
 */
var Torrent1 = new Object();
Torrent1.model = {
    baseUrl: "",
    nomseedbox: "",
    errorServer: false,
    container: {
        listtorrent: null
    },
    loader: {
        listeTorrent: null
    },
    listTorrent: {
        cpt: 0,
        nbtorrents: 0,
        dStatus: { started: 1, paused: 2, checking: 4, hashing: 8, error: 16 },
        original: [],
        liste: [],
        listerecherche: [],
        selectionne: [],
        selectionneid: -1,
        sortcolonne: -1,
        sorttype: 0,
        changed: false
    },
    seedbox: {
        seedboxs: null,
        changed: false
    },
    detailsTorrent: {
        original: [],
        liste: []
    },
    createTorrent: {
        folder: {
            loader: null,
            liste: [],
            hauteurArbre: 0
        }
    },
    filesTorrent: {
        original: [],
        priorite: ["Ne pas télécharger", "Normal", "Haute"],
        liste: [],
        selectionne: [],
        selectionneid: -1,
        hauteurArbre: 0,
        selectionneno: [],
        changed: false,
        getPriorite: function (noprio) {
            if (noprio < 0)
                return '?';
            return this.priorite[noprio];
        }
    },
    trackersTorrent: {
        original: [],
        type: [0, "http", "udp", "dht"],
        liste: [],
        estPrive: function (url) {
            return(
                (/(http|https|udp):\/\/[a-z0-9-\.]+\.[a-z]{2,4}((:(\d){2,5})|).*\/an.*\?.+=.+/i).test(url) ||
                    (/(http|https|udp):\/\/[a-z0-9-\.]+\.[a-z]{2,4}((:(\d){2,5})|)\/.*[0-9a-z]{8,32}\/an/i).test(url) ? 1 : 0 );
        },
        nom: function (url) {
            return url.match(/(http|https|udp):\/\/([^\/]+)/i);
        }

    }
};