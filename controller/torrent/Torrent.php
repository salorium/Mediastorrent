<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 23/03/14
 * Time: 15:03
 */

namespace controller\torrent;


use core\Controller;

class Torrent extends Controller {
    public $layout = "connecter";
    function all(){
        $this->set(array(
            "seedbox"=> \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }

    function add(){

    }
    function send(){
        $this->set(array(
            "file"=> $_FILES,
            "post"=> $_POST
        ));
    }
    function infofichier(){

        if (isset ( $_FILES ['torrentfile'] )) {
            if( is_array($_FILES['torrentfile']['name']) )
            {

                for ($i = 0; $i<count($_FILES['torrentfile']['name']); ++$i)
                {
                    $files[] = array
                    (
                        'name' => $_FILES['torrentfile']['name'][$i],
                        'tmp_name' => $_FILES['torrentfile']['tmp_name'][$i],
                        'error' => $_FILES ['torrentfile'] ['error'][$i]
                    );
                }

            }else{
                $files[] = $_FILES['torrentfile'];

            }
            $torrents = null;
            foreach ($files as $file){
                $torrent = null;
                $torrent['erreur']= -1;
                $torrent['nom']= $file["name"];
                if(pathinfo($file["name"],PATHINFO_EXTENSION)!="torrent")
                    $file["name"].= ".torrent";
                $des = DS."tmp".DS.$file["name"];
                $torrent['nom']= $file["name"];
                $ok = move_uploaded_file($file['tmp_name'],$des);
                if ($ok ){
                    $to = new \model\simple\Torrent($des);
                    //$torrents[]= array($to->getFileName(),$to->info["name"]);
                    if ($to->errors()){
                        $torrent['status']= "ErreurFichier";
                    }else{
                        $info = $to->info;
                        $f = null;

                        if (isset ( $info ['files'] )){
                            foreach ( $info ['files'] as $key => $tfile ) {
                                $nom = $info ['name'].DS.implode ( DS, $tfile ['path'] );
                                if (in_array ( strtolower ( pathinfo ( $nom, PATHINFO_EXTENSION ) ), Thumbnailers::$videoExtensions )) {
                                    $torrent["erreur"] = 0;
                                    $fi ["nom"] = basename($nom);
                                    $fi ["ext"] = pathinfo ( $nom, PATHINFO_EXTENSION );
                                   // $fi ["nomaff"] = formatNomAff ( $fi ["nom"] );
                                    $f [] = $fi;
                                }
                            }
                        }
                        else if (in_array ( strtolower ( pathinfo ( $info ['name'], PATHINFO_EXTENSION ) ), Thumbnailers::$videoExtensions )) {
                            $torrent["erreur"] = 0;
                            $fi ["nom"] = basename($info ['name']);
                            $fi ["ext"] = pathinfo ( $info ['name'], PATHINFO_EXTENSION );
                            //$fi ["nomaff"] = formatNomAff ( $fi ["nom"] );
                            $f [] = $fi;
                        }
                        if (is_null($f)){
                            $to["status"] = "Aucun fichier compatible avec le site (" . Thumbnailers::getStringExtension () . ")";
                        }else{
                            $to["file"] = $f;
                        }
                    }
                    unlink($des);
                }
            }
            /*$tor = null;
            foreach( $files as $file )
            {
                $ufile = $file['name'];
                if(pathinfo($ufile,PATHINFO_EXTENSION)!="torrent")
                    $ufile.=".torrent";
                $nomm = md5(uniqid(rand(), true));
                $to = null;
                $to["name"]=$file['name'];
                $to["erreur"] = -1;
                $ok = move_uploaded_file($file['tmp_name'],"/home/admin/salorium/log/".$nomm.".torrent");
                if ($ok ){
                    $torrent = new Torrent ( "/home/admin/salorium/log/".$nomm.".torrent" );
                    if ($torrent->errors ()) {
                        $to["status"] = "FailedFile";
                    }else{

                        $info = $torrent->info;
                        $f = null;

                        if (isset ( $info ['files'] )){
                            foreach ( $info ['files'] as $key => $tfile ) {
                                $nom = $topDirectory.$info ['name']."/" .implode ( '/', $tfile ['path'] );
                                if (in_array ( strtolower ( pathinfo ( $nom, PATHINFO_EXTENSION ) ), Thumbnailers::$videoExtensions )) {
                                    $to["erreur"] = 0;
                                    $fi ["nom"] = basename($nom);
                                    $fi ["ext"] = pathinfo ( $nom, PATHINFO_EXTENSION );
                                    $fi ["nomaff"] = formatNomAff ( $fi ["nom"] );
                                    $f [] = $fi;
                                }
                            }
                        }
                        else if (in_array ( strtolower ( pathinfo ( $info ['name'], PATHINFO_EXTENSION ) ), Thumbnailers::$videoExtensions )) {
                            $to["erreur"] = 0;
                            $fi ["nom"] = basename($topDirectory.$info ['name']."/".$info ['name']);
                            $fi ["ext"] = pathinfo ( $info ['name'], PATHINFO_EXTENSION );
                            $fi ["nomaff"] = formatNomAff ( $fi ["nom"] );
                            $f [] = $fi;
                        }
                        if (is_null($f)){
                            $to["status"] = "Aucun fichier compatible avec le site (" . Thumbnailers::getStringExtension () . ")";
                        }else{
                            $to["file"] = $f;
                        }

                    }
                    unlink("/home/admin/salorium/log/".$nomm.".torrent");

                }else{
                    $to["status"] = "Erreur de déplacement ou upload code erreur =>".$file['error'];
                }
                $tor[]=$to;
            }
            $t ["torrent"] = $tor;
            $t ["rep"] = parCourBdd ( 0, "/" );
            $j ["data"] = $t;*/
        } else{
            /*$j['status'] = "NoFichier";
            $j['erreur'] = -1;*/
        }
        $this->set(array(
            "file"=> $files,
            "torrent"=> $torrent
        ));
    }

} 