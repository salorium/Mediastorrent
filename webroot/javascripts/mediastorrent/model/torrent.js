/**
 * Created by salorium on 15/03/14.
 */
var Torrent = new Object();
Torrent.model =  {
    hauteurTorrent : 0,
    baseUrl:"",
    nomseedbox:"",
    listeselectionnee: [],
    torrentselectionneedetail:null,
    dStatus : { started : 1, paused : 2, checking : 4, hashing : 8, error : 16 },
    seedboxs : null,
    listeoriginal :[],
    cpt:0,
    changedurl:false,
    liste:[],
    sortcolonne: -1,
    container :{
        listtorrent : null

    },
    nbtorrents:0,
    updated : true
};