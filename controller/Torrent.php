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
use model\xmlrpc\rXMLRPCCommand;


class Torrent extends Controller {
    function liste($login=null,$keyconnexion=null,$cid=null,$hashtorrentselectionne=null){
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
        if ($req->success(false)){
            Debug::endTimer("rtorrent");
            $i = 0;
            $tmp=array();
            $status = array( 'started'=>1,'paused'=>2, 'checking'=>4,'hashing'=>8,'error'=>16);
            $i = preg_match_all("/<array><data>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<\/data><\/array>/Us",$req->val,$tmp1);
           for ( $ii=0;$ii<$i;$ii++){
                $torrent=null;
                $state = 0;
                $is_open =$tmp1[2+4*1][$ii];
                $is_hash_checking  = $tmp1[2+4*2][$ii];
                $is_hash_checked  = $tmp1[2+4*3][$ii];
                $get_state = $tmp1[2+4*4][$ii];
                $get_hashing  = $tmp1[2+4*24][$ii];
                $is_active  = $tmp1[2+4*29][$ii];
                $msg  = $tmp1[2+4*30][$ii];
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
                $torrent[]=$tmp1[2+4*5][$ii];//nom 1
                $torrent[]=intval ($tmp1[2+4*6][$ii]);//taille 2
                $get_completed_chunks = $tmp1[2+4*7][$ii];
                $get_hashed_chunks = $tmp1[2+4*25][$ii];
                $get_size_chunks = $tmp1[2+4*8][$ii];
                $chunks_processing = ($is_hash_checking==0) ? $get_completed_chunks : $get_hashed_chunks;
                $done = floor($chunks_processing/$get_size_chunks*1000);
                $torrent[]=$done;// 3
                $torrent[]=intval ($tmp1[2+4*9][$ii]);//downloaded 4
                $torrent[]=intval ($tmp1[2+4*10][$ii]);//Uploaded 5
                $torrent[]=intval ($tmp1[2+4*11][$ii]);//ratio 6
                $torrent[]=intval ($tmp1[2+4*12][$ii]);//UL 7
                $torrent[]=intval ($tmp1[2+4*13][$ii]);//DL 8
                $get_chunk_size = $tmp1[2+4*14][$ii];
                $torrent[]= ($tmp1[2+4*13][$ii] >0 ? floor(($get_size_chunks-$get_completed_chunks)*$get_chunk_size/$tmp1[2+4*13][$ii]):-1);//Eta 9 (Temps restant en seconde)
                /*$get_peers_not_connected = $tmp1[2+4*17][$ii];
                $get_peers_connected = $tmp1[2+4*18][$ii];
                $get_peers_all = $get_peers_not_connected+$get_peers_connected;*/
                  $torrent[] = intval ($tmp1[2+4*16][$ii]); //Peer Actual 10
                    $torrent[] = intval ($tmp1[2+4*19][$ii]); //Seed Actual 11
                    $seeds=0;
                    foreach(explode("#",$tmp1[2+4*39][$ii]) as $k=> $v){
                        $seeds += $v;
                    }
                    $peers=0;
                    foreach(explode("#",$tmp1[2+4*40][$ii]) as $k=> $v){
                        $peers += $v;
                    }
                    $torrent[] = $peers; //Peer total 12
                    $torrent[] = $seeds; //Seed tota 13


                    $torrent[] = intval ($tmp1[2+4*20][$ii]);//Taille restant 14
                    $torrent[] = intval ($tmp1[2+4*21][$ii]);//Priority 15 (0 ne pas télécharger, 1 basse, 2 moyenne, 3 haute)
                    $torrent[] = intval ($tmp1[2+4*22][$ii]);//State change 16 (dernière date de change d'état)
                    $torrent[] = intval ($tmp1[2+4*23][$ii]);//Skip total Contiens les rejets en mo 17
                    $torrent[] = $tmp1[2+4*26][$ii];//Base Path 18
                    $torrent[] = intval ($tmp1[2+4*27][$ii]);//Date create 19
                    $torrent[] = intval ($tmp1[2+4*28][$ii]);//Focus tracker 20
                    /*try {
                        torrent.comment = this.getValue(values,31);
                        if(torrent.comment.search("VRS24mrker")==0)
                            torrent.comment = decodeURIComponent(torrent.comment.substr(10));
                    } catch(e) { torrent.comment = ''; }*/
                  $torrent[] = intval ($tmp1[2+4*32][$ii]);//Torrent free diskspace 21
                   $torrent[] = intval ($tmp1[2+4*33][$ii]);//Torrent is private 22
                   $torrent[] = intval ($tmp1[2+4*34][$ii]);//Torrent is multifile 23
                   $torrent[] = preg_replace("#\n#","",$tmp1[2+4*42][$ii]);//Torrent seed time 24
                   $torrent[] = preg_replace("#\n#","",$tmp1[2+4*43][$ii]);//Torrent add time 25
                   $torrent[] = $msg;//Message tracker 26
                   $torrent[] =$tmp1[2+4*0][$ii];//Hash 27
                   if ( $hashtorrentselectionne == $tmp1[2+4*0][$ii])
                       $tor = $torrent;
                   $tmp[$tmp1[2+4*0][$ii]]= $torrent;
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

                if (!(\core\Memcached::value("detaillist".\config\Conf::$portscgi,sha1($ncid.$hashtorrentselectionne),$data,60*5))){
                    trigger_error("Impossible de mettre des données dans le cache");
                }
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
        $clefunique = null;
        $typemedias = null;
        if (isset($_REQUEST["mediastorrent"])){
            $tmpclefunique = null;
            for ( $idtorrent = 0 ; $idtorrent < $_REQUEST["nbtorrents"];$idtorrent++){
                if ( isset( $_REQUEST["torrent".$idtorrent."addbibli"])){
                    $typemedias[$_REQUEST["torrent".$idtorrent."hash"]]=$_REQUEST["torrent".$idtorrent."type"];
                    switch($_REQUEST["torrent".$idtorrent."type"] ){
                        case 'film':
                            if ($_REQUEST["torrent".$idtorrent."recherche"] === "manuel" ){
                                //Manuel
                                $titre = trim($_REQUEST["torrent".$idtorrent."detailstitre"]);
                                $otitre = trim($_REQUEST["torrent".$idtorrent."detailstitreoriginal"]);
                                $synopsis = trim($_REQUEST["torrent".$idtorrent."detailssynopsis"]);
                                $genre = explode(",",$_REQUEST["torrent".$idtorrent."detailsgenre"]);
                                array_walk ($genre, create_function('&$val', '$val = trim($val);'));
                                array_walk ($genre, create_function('&$val', '$val = strtolower($val);'));
                                array_walk ($genre, create_function('&$val', '$val = ucfirst($val);'));
                                $acteurs = explode(",",$_REQUEST["torrent".$idtorrent."detailsacteur"]);
                                array_walk ($acteurs, create_function('&$val', '$val = trim($val);'));
                                array_walk ($acteurs, create_function('&$val', '$val = strtolower($val);'));
                                array_walk ($acteurs, create_function('&$val', '$val = ucwords($val);'));
                                $acteurs = implode(", ",$acteurs);
                                $realisateurs = explode(",",$_REQUEST["torrent".$idtorrent."detailsrealisateur"]);
                                array_walk ($realisateurs,create_function('&$val', '$val = trim($val);'));
                                array_walk ($realisateurs,create_function('&$val', '$val = strtolower($val);'));
                                array_walk ($realisateurs,create_function('&$val', '$val = ucwords($val);'));
                                $realisateurs = implode(", ",$realisateurs);
                                $anneeprod = trim($_REQUEST["torrent".$idtorrent."detailsanneeprod"]);
                                $urlposter = trim($_REQUEST["torrent".$idtorrent."detailsposter"]);
                                $urlbackdrop = trim($_REQUEST["torrent".$idtorrent."detailsbackdrop"]);
                                $infos["Titre"]= $titre;
                                $infos["Titre original"]= $otitre;
                                $infos["Genre"]= implode(", ",$genre);
                                $infos["Réalisateur(s)"]=$realisateurs;
                                $infos["Acteur(s)"]=$acteurs;
                                $infos["Année de production"]=$anneeprod;
                                $infos["Synopsis"]=$synopsis;
                                $film = \model\mysql\Film::ajouteFilm($titre,$otitre,json_encode($infos),$urlposter,$urlbackdrop,$anneeprod,$acteurs,$realisateurs);
                                $idfilm =  $film->id;
                                $film->addGenre($genre);
                            }else{
                                //Auto
                                if ( $_REQUEST["torrent".$idtorrent."typerecherche"] === "local"){
                                    //Local
                                    $idfilm =  $_REQUEST["torrent".$idtorrent."code"];
                                }else{
                                    //Allo
                                    $o["typesearch"]="movie";
                                    $allo = new \model\simple\Allocine($_REQUEST["torrent".$idtorrent."code"],$o);
                                    $infos = $allo->retourneResMovieFormatForBD();
                                    $genre = $infos["Genre"];
                                    $infos["Genre"] = implode(", ",$genre);
                                    $titre = (isset($infos["Titre"])?$infos["Titre"]:$infos["Titre original"]);
                                    $otitre = $infos["Titre original"];
                                    $urlposter = trim($_REQUEST["torrent".$idtorrent."detailsposter"]);
                                    $urlbackdrop = trim($_REQUEST["torrent".$idtorrent."detailsbackdrop"]);
                                    $realisateurs = $infos["Réalisateur(s)"];
                                    $acteurs = $infos["Acteur(s)"];
                                    $anneeprod = $infos["Année de production"];
                                    $film = \model\mysql\Film::ajouteFilm($titre,$otitre,json_encode($infos),$urlposter,$urlbackdrop,$anneeprod,$acteurs,$realisateurs,$_REQUEST["torrent".$idtorrent."code"]);
                                    $idfilm =  $film->id;
                                    $film->addGenre($genre);
                                }
                            }
                            break;
                    }
                }
            }
        }
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
                        $torrent["clefunique"]= \model\simple\String::random(10);
                        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi, array(
                            new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "d.set_custom",array($to->hash_info(),"clefunique",$torrent["clefunique"]) )));
                        $torrent["clefuniqueres"]= ($req->success() ? $req->val : $req->val);

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
            $theSettings->getOnFinishedCommand(array('addbibliotheque',
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'execute').'={'.'php,'.ROOT.DS.'script/addbibliotheque.php,'.\config\Conf::$portscgi.',$'.\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.get_hash').'=,$'.\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.get_base_path').'=,$'.
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.get_base_filename').'=,$'.\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.is_multi_file').'=,$'.\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$portscgi,'d.get_custom')."=clefunique".'}'
            ))
        ));
        if( $req->run()){
            echo "ok";
        }else{
            echo $req->val;
        }
    }
} 