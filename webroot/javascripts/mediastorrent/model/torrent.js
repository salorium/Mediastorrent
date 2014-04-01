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
    downloadFileTorrent: function(hash,no){
        var url = "http://"+Torrent.model.baseUrl+'/torrent/download/'+hash+"/"+no+"/"+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion;

        return url;
    },
    nbtorrents:0,
    updated : true
};