/**
 * Created by salorium on 23/04/14.
 */
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
conversionListeNavigation : function (liste,force){
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
        var dossier = [];
        for( var j = 0; j <Torrent.model.filelisteoriginal.length; j++){
            var v = Torrent.model.filelisteoriginal[j];
            Torrent.model.fileliste[Torrent.model.fileliste.length]= v;
            var paths = v[1].split("/");

            for ( var i= 0; i < paths.length;i++){
                if ( i == paths.length -1){
                    //File
                    if ( i == 0){
                        if (!Torrent.model.filelistenavigation[0])
                            Torrent.model.filelistenavigation[0]=[];
                        Torrent.model.filelistenavigation[0][Torrent.model.filelistenavigation[0].length] = [false,paths[i]];
                    }else{
                        if ( !Torrent.model.filelistenavigation[dossier[paths[i-1]]])
                            Torrent.model.filelistenavigation[dossier[paths[i-1]]]=[];
                        Torrent.model.filelistenavigation[dossier[paths[i-1]]][Torrent.model.filelistenavigation[dossier[paths[i-1]]].length]= [false,paths[i]];
                    }
                }else{
                    //Dossier
                    if ( i == 0){
                        if (!Torrent.model.filelistenavigation[0])
                            Torrent.model.filelistenavigation[0]=[];
                        if ( !dossier[paths[i]] )
                            Torrent.model.filelistenavigation[0][Torrent.model.filelistenavigation[0].length] = [true,paths[i],Torrent.model.filelistenavigation.length];
                    }else{
                        if ( !Torrent.model.filelistenavigation[dossier[paths[i-1]]])
                            Torrent.model.filelistenavigation[dossier[paths[i-1]]]=[];
                        if ( !dossier[paths[i]] )
                            Torrent.model.filelistenavigation[dossier[paths[i-1]]][Torrent.model.filelistenavigation[dossier[paths[i-1]]].length]= [true,paths[i],Torrent.model.filelistenavigation.length];
                    }
                    if ( !dossier[paths[i]] )
                        dossier[paths[i]]=Torrent.model.filelistenavigation.length;
                }



            }
            //Torrent.model.filelistenavigation[ Torrent.model.filelistenavigation.length-1] = vv;


        }
        //Tri par fusion si nécessaire
        /*if (Torrent.model.sortcolonne > -1){
         Torrent.model.fileliste = Base.model.tableau.triFusion(Torrent.model.fileliste,Torrent.model.sortcolonne,Torrent.model.sorttype);
         }*/


    }
},