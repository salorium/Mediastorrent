/**
 * Created by salorium on 15/03/14.
 */
var Torrent = new Object();
Torrent.model =  {
    fileinfospriorite : ["Ne pas télécharger","Normal","Haute"],
    hauteurTorrent : 0,
    baseUrl:"",
    nomseedbox:"",
    listeselectionnee: [],
    listeselectionneeid : -1,
    changeselecttorrent:false,
    torrentselectionneedetail:null,
    dStatus : { started : 1, paused : 2, checking : 4, hashing : 8, error : 16 },
    seedboxs : null,
    listeoriginal :[],
    cpt:0,
    changedurl:false,
    liste:[],
    listerecherche:[],
    fileselectionnee:[],
    fileselectionneenofile:[],
    fileselectionneeid:-1,
    //fileliste:[],
    filelistenavigation:[],
    filenavigationou : 0, //Etage de l'arbre files 0 racine
    filelisteoriginal:[],
    detailliste:[],
    detaillisteoriginal:[],
    sortcolonne: -1,
    container :{
        listtorrent : null

    },
    getPriorite : function( noprio){
        if ( noprio < 0 )
            return '?';
        return Torrent.model.fileinfospriorite[noprio];
    },
    downloadFileTorrent: function(hash,no){
        var url = "http://"+Torrent.model.baseUrl+'/torrent/download/'+hash+"/"+no+"/"+Base.model.utilisateur.login+"/"+Base.model.utilisateur.keyconnexion;

        return url;
    },
    nbtorrents:0,
    updated : true,
    regexsaison : /(s(\d+)|(\d+)x(\d+))/img,
    regexepisode: /e(\d+)/gi,
    regexepisode1 : /(\d+)/gi
};