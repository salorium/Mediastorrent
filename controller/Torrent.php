<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 23/03/14
 * Time: 15:03
 */

namespace controller;


use core\Controller;
use core\Debug;


class Torrent extends Controller {
    function liste($login=null,$keyconnexion=null,$cid=null,$hashtorrentselectionne=null){
        /*if (!is_null($login) && ! is_null($keyconnexion)){
            $u = \core\Memcached::value($login,"user");
            if ( is_null($u)){
                $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($login,$keyconnexion);
                if ( $u)
                    \core\Memcached::value($u->login,"user",$u,60*5);
            }else{
                $u = $u->keyconnexion ===$keyconnexion ? $u:false ;
                if ( is_bool($u)){
                    $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($login,$keyconnexion);
                    if ( $u)
                        \core\Memcached::value($u->login,"user",$u,60*5);
                }
                $u = $u->keyconnexion ===$keyconnexion ? $u:false ;
            }
            \config\Conf::$user["user"]= $u;
        }*/
        \model\simple\Utilisateur::authentificationPourRtorrent($login,$keyconnexion);
        $tor = null ;
        if ( !\config\Conf::$user["user"] ) throw new \Exception("Non User");
        $cmds = array(
            "d.get_hash="/*0*/, "d.is_open="/*1*/, "d.is_hash_checking="/*2*/, "d.is_hash_checked="/*3*/, "d.get_state="/*4*/,
            "d.get_name="/*5*/, "d.get_size_bytes="/*6*/, "d.get_completed_chunks="/*7*/, "d.get_size_chunks="/*8*/, "d.get_bytes_done="/*9*/,
            "d.get_up_total="/*10*/, "d.get_ratio="/*11*/, "d.get_up_rate="/*12*/, "d.get_down_rate="/*13*/, "d.get_chunk_size="/*14*/,
            "d.get_custom1="/*15 A supprimer*/, "d.get_peers_accounted="/*16*/, "d.get_peers_not_connected="/*17*/, "d.get_peers_connected="/*18*/, "d.get_peers_complete="/*19*/,
            "d.get_left_bytes="/*20*/, "d.get_priority="/*21*/, "d.get_state_changed="/*22*/, "d.get_skip_total="/*23*/, "d.get_hashing="/*24*/,
            "d.get_chunks_hashed="/*25*/, "d.get_base_path="/*26*/, "d.get_creation_date="/*27*/, "d.get_tracker_focus="/*28*/, "d.is_active="/*29*/,
            "d.get_message="/*30*/, "d.get_custom2="/*31 A supprimer*/, "d.get_free_diskspace="/*32*/, "d.is_private="/*33*/, "d.is_multi_file="/*34*/,"d.get_throttle_name="/*35*/,"d.get_custom=chk-state"/*36*/,
            "d.get_custom=chk-time"/*37*/,"d.get_custom=sch_ignore"/*38*/,'cat="$t.multicall=d.get_hash=,t.get_scrape_complete=,cat={#}"'/*39*/,'cat="$t.multicall=d.get_hash=,t.get_scrape_incomplete=,cat={#}"'/*40*/,
            'cat=$d.views='/*41*/,"d.get_custom=seedingtime"/*42*/,"d.get_custom=addtime"/*43*/
        );
        $cmd = new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "d.multicall", "main" );
        $res = array();
        foreach ( $cmds as $v){
            $res[] = \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,$v);
        }
        $cmd->addParameters( $res );
        $cnt = count($cmd->params)-1;
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi,$cmd);
        $t= null;
        Debug::startTimer("rtorrent");
        /*$req->success();
        Debug::endTimer("rtorrent");
        $this->set(array(
            //"torrent"=>$req->val,
            "tt"=> $req->vals
        ));
        return true;//*/
        if ($req->success()){
            Debug::endTimer("rtorrent");
            $i = 0;
            $tmp=array();
            $status = array( 'started'=>1,'paused'=>2, 'checking'=>4,'hashing'=>8,'error'=>16);

            while ($i < count($req->val)){
                $torrent=null;
                $state = 0;
                $is_open = $req->val[$i+1];
                $is_hash_checking  = $req->val[$i+2];
                $is_hash_checked  = $req->val[$i+3];
                $get_state = $req->val[$i+4];
                $get_hashing  = $req->val[$i+24];
                $is_active  = $req->val[$i+29];
                $msg  = $req->val[$i+30];
                if($is_open!=0)
                {
                    $state|=$status["started"];
                    if(($get_state==0) || ($is_active==0))
                        $state|=$status["paused"];
                }
                if($get_hashing!=0)
                    $state|=$status["hashing"];
                if($is_hash_checking!=0)
                    $state|=$status["checking"];
                if($msg!="" && $msg!="Tracker: [Tried all trackers.]")
                    $state|=$status["error"];
                $torrent[]=$state;   //state 0
                $torrent[]=$req->val[$i+5];//nom 1
                $torrent[]=intval ($req->val[$i+6]);//taille 2
                $get_completed_chunks = $req->val[$i+7];
                $get_hashed_chunks = $req->val[$i+25];
                $get_size_chunks = $req->val[$i+8];
                $chunks_processing = ($is_hash_checking==0) ? $get_completed_chunks : $get_hashed_chunks;
                $done = floor($chunks_processing/$get_size_chunks*1000);
                $torrent[]=$done;// 3
                $torrent[]=intval ($req->val[$i+9]);//downloaded 4
                $torrent[]=intval ($req->val[$i+10]);//Uploaded 5
                $torrent[]=intval ($req->val[$i+11]);//ratio 6
                $torrent[]=intval ($req->val[$i+12]);//UL 7
                $torrent[]=intval ($req->val[$i+13]);//DL 8
                $get_chunk_size = $req->val[$i+14];
                $torrent[]= ($req->val[$i+13] >0 ? floor(($get_size_chunks-$get_completed_chunks)*$get_chunk_size/$req->val[$i+13]):-1);//Eta 9 (Temps restant en seconde)
                /*$get_peers_not_connected = $req->val[$i+17];
                $get_peers_connected = $req->val[$i+18];
                $get_peers_all = $get_peers_not_connected+$get_peers_connected;*/
                $torrent[] = intval ($req->val[$i+16]); //Peer Actual 10
                $torrent[] = intval ($req->val[$i+19]); //Seed Actual 11
                $seeds=0;
                foreach(explode("#",$req->val[$i+39]) as $k=> $v){
                    $seeds += $v;
                }
                $peers=0;
                foreach(explode("#",$req->val[$i+40]) as $k=> $v){
                    $peers += $v;
                }
                $torrent[] = $peers; //Peer total 12
                $torrent[] = $seeds; //Seed tota 13


                $torrent[] = intval ($req->val[$i+20]);//Taille restant 14
                $torrent[] = intval ($req->val[$i+21]);//Priority 15 (0 ne pas télécharger, 1 basse, 2 moyenne, 3 haute)
                $torrent[] = intval ($req->val[$i+22]);//State change 16 (dernière date de change d'état)
                $torrent[] = intval ($req->val[$i+23]);//Skip total Contiens les rejets en mo 17
                $torrent[] = $req->val[$i+26];//Base Path 18
                $torrent[] = intval ($req->val[$i+27]);//Date create 19
                $torrent[] = intval ($req->val[$i+28]);//Focus tracker 20
                /*try {
                    torrent.comment = this.getValue(values,31);
                    if(torrent.comment.search("VRS24mrker")==0)
                        torrent.comment = decodeURIComponent(torrent.comment.substr(10));
                } catch(e) { torrent.comment = ''; }*/
                $torrent[] = intval ($req->val[$i+32]);//Torrent free diskspace 21
                $torrent[] = intval ($req->val[$i+33]);//Torrent is private 22
                $torrent[] = intval ($req->val[$i+34]);//Torrent is multifile 23
                $torrent[] = preg_replace("#\n#","",$req->val[$i+42]);//Torrent seed time 24
                $torrent[] = preg_replace("#\n#","",$req->val[$i+43]);//Torrent add time 25
                $torrent[] = $msg;//Message tracker 26
                $torrent[] =$req->val[$i];//Hash 27
                if ( $hashtorrentselectionne == $req->val[$i])
                    $tor = $torrent;
                $tmp[$req->val[$i]]= $torrent;
                $i=$i+44;

            }
            $data = $tmp;
            if (!is_null($cid)){
                if ($anc = \core\Memcached::value("torrentlist".\config\Conf::$portscgi,$cid)){
                    foreach ($anc as $k=>$v){
                        if (!isset($tmp[$k]))
                            $tmp[$k]=false;
                        foreach($v as $kk=>$vv){
                            if (isset($tmp[$k][$kk]) && $tmp[$k][$kk] == $vv){
                                unset($tmp[$k][$kk]);
                            }
                        }
                        if (count($tmp[$k]) ==0)
                            unset($tmp[$k]);
                    }
                }
            }

            $ncid = \model\simple\String::random(5);
            if (!(\core\Memcached::value("torrentlist".\config\Conf::$portscgi,$ncid,$data,60*5)))
                trigger_error("Impossible de mettre des données dans le cache");
            $t[]= $tmp;
            $t[]= $ncid;
            $path = ROOT.DS."..".DS."rtorrent".DS."data";
            $t[]= disk_total_space($path)-disk_free_space($path);
            $t[]= disk_total_space($path);


            $cmds = array(
                "get_up_rate","get_upload_rate", "get_up_total","get_down_rate","get_download_rate", "get_down_total"
            );
            $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi);

            foreach( $cmds as $cmd )
                $req->addCommand( new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, $cmd ) );
            if($req->success())
                $t[]= $req->val;

        }
        if(is_null($t)) trigger_error("Impossible de se connecter à rtorrent :(");
        $torrent = null;
            if ( !is_null($hashtorrentselectionne)){
                $tmp = $tor;
                $data = $tmp;
                if (!is_null($cid)){
                    if ($anc = \core\Memcached::value("detaillist".\config\Conf::$portscgi,sha1($cid.$hashtorrentselectionne))){
                        foreach ($anc as $k=>$v){
                            if (isset($tmp[$k]) && $tmp[$k] == $v){
                                  unset($tmp[$k]);
                            }
                         }
                    }
                }

                if (!(\core\Memcached::value("detaillist".\config\Conf::$portscgi,sha1($ncid.$hashtorrentselectionne),$data,60*5)))
                    trigger_error("Impossible de mettre des données dans le cache");
                $torrent["detail"]= $tmp;
                $cmds = array(
                    "f.get_path=", "f.get_completed_chunks=", "f.get_size_chunks=", "f.get_size_bytes=", "f.get_priority=","f.prioritize_first=","f.prioritize_last="
                );
                $cmd = new \model\xmlrpc\rXMLRPCCommand( \config\Conf::$portscgi,"f.multicall", array( $hashtorrentselectionne, "" ) );

                foreach($cmds as $prm){
                    $cmd->addParameter( \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,$prm) );
                }
                $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi,$cmd);
                $files  = null;
                $to = null;
                if(!$req->success()){
                    trigger_error("Impossible de récupéré la liste des fichiers de ".$hashtorrentselectionne);
                    $files = $req->val;
                }else{
                    $taille = count($req->val);
                    $j=0;
                    for($i=0;$i<$taille;$i+=7 ){
                        $files[]= array($j,$req->val[$i],$req->val[$i+1],$req->val[$i+2],$req->val[$i+3],$req->val[$i+4],$req->val[$i+5],$req->val[$i+6]);
                        $j++;
                    }
                    $tmp = $files;
                    $data = $tmp;
                    if (!is_null($cid)){
                        if ($anc = \core\Memcached::value("fileslist".\config\Conf::$portscgi,sha1($cid.$hashtorrentselectionne))){
                            foreach ($anc as $k=>$v){
                                if (!isset($tmp[$k]))
                                    $tmp[$k]=false;
                                foreach($v as $kk=>$vv){
                                    if (isset($tmp[$k][$kk]) && $tmp[$k][$kk] == $vv){
                                        unset($tmp[$k][$kk]);
                                    }
                                }
                                if (count($tmp[$k]) ==0)
                                    unset($tmp[$k]);
                            }
                        }
                    }

                    if (!(\core\Memcached::value("fileslist".\config\Conf::$portscgi,sha1($ncid.$hashtorrentselectionne),$data,60*5)))
                        trigger_error("Impossible de mettre des données dans le cache");
                    $torrent["files"]= $tmp;
                }

            }

        $this->set(array(
            "torrent"=>$t,
            "torrentselectionnee"=>$torrent,
            "hashtorrent"=>$hashtorrentselectionne,
            "host"=>HOST,
            "seedbox"=> \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }
    function pause($login=null,$keyconnexion=null){
        \model\simple\Utilisateur::authentificationPourRtorrent($login,$keyconnexion);
        if ( !\config\Conf::$user["user"] ) throw new \Exception("Non User");
        $cmds = array(
            "d.stop"
        );

        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi);
        foreach($_REQUEST["hash"] as $h)
            foreach($cmds as $cmd)
                $req->addCommand( new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, $cmd, $h ) );
        $r = ($req->success() ? $req->val : false);

        $this->set(array(
            "rtorrent"=>$r,
            "seedbox"=> \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }

    function start($login=null,$keyconnexion=null){
        \model\simple\Utilisateur::authentificationPourRtorrent($login,$keyconnexion);
        if ( !\config\Conf::$user["user"] ) throw new \Exception("Non User");
        $cmds = array("d.open","d.start");

        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi);
        foreach($_REQUEST["hash"] as $h)
            foreach($cmds as $cmd)
                $req->addCommand( new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, $cmd, $h ) );
        $r = ($req->success() ? $req->val : false);

        $this->set(array(
            "rtorrent"=>$r,
            "seedbox"=> \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }
    function stop($login=null,$keyconnexion=null){
        \model\simple\Utilisateur::authentificationPourRtorrent($login,$keyconnexion);
        if ( !\config\Conf::$user["user"] ) throw new \Exception("Non User");
        $cmds = array("d.stop","d.close");

        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi);
        foreach($_REQUEST["hash"] as $h)
            foreach($cmds as $cmd)
                $req->addCommand( new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, $cmd, $h ) );
        $r = ($req->success() ? $req->val : false);

        $this->set(array(
            "rtorrent"=>$r,
            "seedbox"=> \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }

    function recheck($login=null,$keyconnexion=null){
        \model\simple\Utilisateur::authentificationPourRtorrent($login,$keyconnexion);
        if ( !\config\Conf::$user["user"] ) throw new \Exception("Non User");
        $cmds = array("d.check_hash");

        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi);
        foreach($_REQUEST["hash"] as $h)
            foreach($cmds as $cmd)
                $req->addCommand( new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, $cmd, $h ) );
        $r = ($req->success() ? $req->val : false);

        $this->set(array(
            "rtorrent"=>$r,
            "seedbox"=> \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }
    function delete($login=null,$keyconnexion=null){
        \model\simple\Utilisateur::authentificationPourRtorrent($login,$keyconnexion);
        if ( !\config\Conf::$user["user"] ) throw new \Exception("Non User");
        $cmds = array("d.erase");

        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi);
        foreach($_REQUEST["hash"] as $h)
            foreach($cmds as $cmd)
                $req->addCommand( new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, $cmd, $h ) );
        $r = ($req->success() ? $req->val : false);

        $this->set(array(
            "rtorrent"=>$r,
            "seedbox"=> \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }
    function deleteall($login=null,$keyconnexion=null){
        \model\simple\Utilisateur::authentificationPourRtorrent($login,$keyconnexion);
        if ( !\config\Conf::$user["user"] ) throw new \Exception("Non User");

        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi);
        foreach($_REQUEST["hash"] as $h){
            $req->addCommand( new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi,"d.get_name",$h) );
            $req->addCommand( new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi,"d.set_custom1",array($h,"1")) );
            $req->addCommand( new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi,"d.erase",$h));
        }

        $r = ($req->success() ? $req->val : $req->val);
        $taille = count($r);
        $d = array();
        for($i= 0; $i < $taille; $i+=3){
            if ($r[$i] !== "" && $r[$i+1] == 1 && $r[$i+2] == 0 ){
                $d[$r[$i]] = true;
            }else{
                $d[$r[$i]] = false;
            }
        }
        $this->set(array(
            "torrent"=>$d,
            "seedbox"=> \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));

    }
    function send($login=null,$keyconnexion=null){
        \model\simple\Utilisateur::authentificationPourRtorrent($login,$keyconnexion);
        if ( !\config\Conf::$user["user"] ) throw new \Exception("Non User");
        $erreur = 1;
        $torrents = null;
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


            foreach ($files as $file){
                $erreur = 0;
                $torrent = null;
                $torrent['erreur']= 1;
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
                        $torrent['status']= "Erreur du fichier torrent";
                    }else{
                        $torrent['erreur']= 0;
                        $torrent["status"]= \model\xmlrpc\rTorrent::sendTorrent($to,!isset($_REQUEST['autostart']));

                    }
                    unlink($des);
                }else{
                    $torrent['status'] = "Erreur lors de l'upload | Code d'erreur => ".$file["error"];
                }
                $torrents[]= $torrent;
            }
        }else{
            $status = "Pas de fichier envoyer";
        }
        $this->set(array(
            "torrent"=>$torrents,
            "erreur"=> $erreur,
            "status"=>$status ,

            "post"=> $_REQUEST,
            "seedbox"=> \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }
    function details($hashtorrentselectionne,$login=null,$keyconnexion=null){
        \model\simple\Utilisateur::authentificationPourRtorrent($login,$keyconnexion);
        if ( !\config\Conf::$user["user"] ) throw new \Exception("Non User");
        $cmds = array(
            "f.get_path=", "f.get_completed_chunks=", "f.get_size_chunks=", "f.get_size_bytes=", "f.get_priority=","f.prioritize_first=","f.prioritize_last="
        );
        $cmd = new \model\xmlrpc\rXMLRPCCommand( \config\Conf::$portscgi,"f.multicall", array( $hashtorrentselectionne, "" ) );

        foreach($cmds as $prm){
            $cmd->addParameter( \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,$prm) );
        }
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi,$cmd);
        $files  = null;
        $to = null;
        if(!$req->success()){
            trigger_error("Impossible de récupéré la liste des fichiers de ".$hashtorrentselectionne);
            $files = $req->val;
        }else{
            $taille = count($req->val);
            $j=0;
            for($i=0;$i<$taille;$i+=7 ){
                $files[]= array($j,$req->val[$i],$req->val[$i+1],$req->val[$i+2],$req->val[$i+3],$req->val[$i+4],$req->val[$i+5],$req->val[$i+6]);
                $j++;
            }
            $to["files"]= $files;
        }

        $this->set(array(
            "torrentselectionnee"=>$to,
            "host"=>HOST,
            "hashtorrent"=>$hashtorrentselectionne,
            "seedbox"=> \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }
    function download($hashtorrentselectionne,$nofile,$login=null,$keyconnexion=null){
        \model\simple\Utilisateur::authentificationPourRtorrent($login,$keyconnexion);
        if ( !\config\Conf::$user["user"] ) throw new \Exception("Non User");
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi,
            new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "f.get_frozen_path", array($hashtorrentselectionne,intval($nofile))) );
        if($req->success())
        {
            $filename = $req->val[0];
            if($filename=='')
            {
                $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi, array(
                    new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "d.open", $hashtorrentselectionne ),
                    new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "f.get_frozen_path", array($hashtorrentselectionne,intval($nofile)) ),
                    new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "d.close", $hashtorrentselectionne ) ) );
                if($req->success())
                    $filename = $req->val[1];
            }
            echo $filename;
            var_dump( $req->val);
            die();
            \model\simple\Download::sendFile($filename);
        }
        throw new \Exception("FILE NOT FOUND");
    }
    function setPrioriteFile($hashtorrentselectionne,$prio,$login=null,$keyconnexion=null){
        \model\simple\Utilisateur::authentificationPourRtorrent($login,$keyconnexion);
        if ( !\config\Conf::$user["user"] ) throw new \Exception("Non User");
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi);
        foreach($_REQUEST["nofiles"] as $v)
            $req->addCommand( new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "f.set_priority", array($hashtorrentselectionne, intval($v), intval($prio)) ) );
        $req->addCommand( new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi,"d.update_priorities", $hashtorrentselectionne) );
        if($req->success())
            $result = $req->val;
    }
    function init($login=null,$keyconnexion=null){
        \model\simple\Utilisateur::authentificationPourRtorrent($login,$keyconnexion);
        $theSettings = \model\xmlrpc\rTorrentSettings::get(\config\Conf::$portscgi,true);
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi, array(
            $theSettings->getOnFinishedCommand(array("seedingtime",
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.set_custom').'=seedingtime,"$'.\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'execute_capture').'={date,+%s}"')),
            $theSettings->getOnInsertCommand(array("addtime",
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.set_custom').'=addtime,"$'.\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'execute_capture').'={date,+%s}"')),

            $theSettings->getOnHashdoneCommand(array("seedingtimecheck",
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'branch=').'$'.\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'not=').'$'.\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.get_complete=').',,'.
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.get_custom').'=seedingtime,,"'.\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.set_custom').'=seedingtime,$'.\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.get_custom').'=addtime'.'"')),

            \model\xmlrpc\rTorrentSettings::get(\config\Conf::$portscgi)->getOnEraseCommand(array('erasedata',
            \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'branch=').\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.get_custom1').'=,"'.\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'execute').'={rm,-r,$'.\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.get_base_path').'=}"')),

        ));
        if( $req->run()){
            echo "ok";
        }else{
            echo $req->val;
        }
    }
} 