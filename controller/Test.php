<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 11/03/14
 * Time: 01:23
 */

namespace controller;


use core\Controller;
use model\mysql\Film;
use model\simple\Mail;
use model\xmlrpc\rTorrentSettings;


class Test extends Controller
{
    function rt($ports)
    {
        \config\Conf::$portscgi = $ports;
        var_dump(\model\xmlrpc\rTorrentSettings::get(\config\Conf::$portscgi, true));
        var_dump(\model\xmlrpc\rXMLRPCRequest::$query);
    }

    function addFilm($id)
    {
        $o["typesearch"] = "movie";
        $allo = new \model\simple\Allocine($id, $o);
        $infos = $allo->retourneResMovieFormatForBD();
        $genre = $infos["Genre"];
        $infos["Genre"] = implode(", ", $genre);
        $titre = (isset($infos["Titre"]) ? $infos["Titre"] : $infos["Titre original"]);
        $otitre = $infos["Titre original"];
        $urlposter = "";
        $urlbackdrop = "";
        $realisateurs = $infos["Réalisateur(s)"];
        $acteurs = "";
        if (isset($infos["Acteur(s)"]))
            $acteurs = $infos["Acteur(s)"];
        $anneeprod = $infos["Année de production"];
        $film = \model\mysql\Film::ajouteFilm($titre, $otitre, json_encode($infos), $urlposter, $urlbackdrop, $anneeprod, $acteurs, $realisateurs, $id);
        $film->addGenre($genre);
        $film->addGenre("Comédiatation");
    }

    function isql()
    {
        $querys = file_get_contents(ROOT . DS . "mysql" . DS . "mediastorrent.sql");
        $t = \core\Mysqli::multiquery($querys);
        \core\Mysqli::getObjectAndClose(false);
    }

    function portrtorrent($portscgi)
    {
        //\config\Conf::$portscgi = $portscgi;
        $this->set("rtorrent", rTorrentSettings::get($portscgi)->port);
    }

    function tfilm()
    {
        \model\mysql\Torrentfilm::rechercheParNumFileHashClefunique(0, 'FA0C487D79DD07DB1BE85E9639D9E5B112DD39EE', '9JkOBaF1Hs');
        //\model\mysql\Torrentfilm::addTorrentFilm("wHOXNvBDDy", "0", "ddd", "salorium", "BigTerra2", "dd", "a", false);
    }

    function clefunique()
    {
        echo "FIN => " . \model\mysql\Torrentfilm::getClefUnique();
    }
    /*function addFilm(){
        $f = Film::ajouteFilm("Titi","Titi","az","az","dd");
        echo $f->id;
        die();
    }*/
    /*function mailo($login,$mail,$mdp){
        $this->set(array(
            "mail"=>Mail::activationMotDePasse($mdp,$login,$mail)
        ));
    }*/
    function mail($mail)
    {
        // Plusieurs destinataires
        $to = "" . $mail . "";

        // Sujet
        $subject = 'Calendrier des anniversaires pour Août';

        // message
        $message = '
     <html>
      <head>
       <title>Calendrier des anniversaires pour Août</title>
      </head>
      <body>
       <p>Voici les anniversaires à venir au mois d\'Août !</p>
       <table>
        <tr>
         <th>Personne</th><th>Jour</th><th>Mois</th><th>Année</th>
        </tr>
        <tr>
         <td>Josiane</td><td>3</td><td>Août</td><td>1970</td>
        </tr>
        <tr>
         <td>Emma</td><td>26</td><td>Août</td><td>1973</td>
        </tr>
       </table>
      </body>
     </html>
     ';

        // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        // En-têtes additionnels
        // $headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
        $headers .= 'From: admin@' . $_SERVER["HTTP_HOST"] . '' . "\r\n";
        //$headers .= 'Cc: anniversaire_archive@example.com' . "\r\n";
        //$headers .= 'Bcc: anniversaire_verif@example.com' . "\r\n";

        // Envoi
        $this->set(array(
            "mail" => mail($to, $subject, $message, $headers)
        ));
    }

    function allocine($re)
    {
        $o["typesearch"] = "movie";
        $all = new \model\simple\Allocine($re, $o);
        $this->set(array(
            "film" => $all->retourneResMovie()
        ));
    }

    function iso3166()
    {
        \model\simple\Iso31::getIso3166();
    }

    function tmdb($re)
    {
        $tmdb = new \model\simple\TheMovieDb();
        $this->set(array(
            "film" => $tmdb->getMovieFormat($re)
        ));
    }

    function accerole($role)
    {
        $num = \config\Conf::$rolenumero[$role];
        $compteurarray = null;
        $tass = null;
        do {
            $role = \config\Conf::$numerorole[$num];
            if (is_array($role)) {
                if (is_null($compteurarray)) {
                    $compteurarray = 0;
                }
                $r = $role;
                $role = $role[$compteurarray];
                $compteurarray++;
                if ($compteurarray == count($r)) {
                    $compteurarray = null;
                    $num--;
                }

            } else {
                $num--;
            }

            if ($role === \config\Conf::$numerorole[0]) {
                $repertoire = ROOT . DS . "controller";
            } else {
                $repertoire = ROOT . DS . "controller" . DS . \strtolower($role);

            }


            if (\file_exists($repertoire)) {
                $MyDirectory = \opendir($repertoire);
                while ($Entry = @\readdir($MyDirectory)) {
                    if (!is_dir($repertoire . DS . $Entry) && $Entry != '.' && $Entry != '..') {
                        if ($role === \config\Conf::$numerorole[0]) {
                            $cname = '\controller\\' . pathinfo($Entry, PATHINFO_FILENAME);
                        } else {
                            $cname = '\controller\\' . strtolower($role) . '\\' . pathinfo($Entry, PATHINFO_FILENAME);
                        }
                        $c = new $cname($this->request, $this->debug);
                        $cn = explode("\\", $cname);
                        $cn = $cn[count($cn) - 1];
                        $v = get_class_methods($c);
                        if (!isset ($tass[$cn]))
                            $tass[$cn] = array();
                        foreach ($v as $k => $vv) {
                            if (!in_array($vv, $tass[$cn]))
                                $tass[$cn][] = $vv;
                        }
                    }

                }
                \closedir($MyDirectory);

            }
        } while ($num > -1);
        $this->set(array(
            "droits" => $tass
        ));
    }

    function getAllMemcached()
    {
        $n = \core\Memcached::getInstance();
        //var_dump($n->getAllKeys());
        $tab = null;
        $a = $n->getAllKeys();
        foreach ($a as $k => $v) {
            $tab[$v] = $n->get($v);
        }
        //\core\Memcached::value(\config\Conf::$user["user"]->login,"user");
        // \core\Memcached::value(\config\Conf::$user["user"]->login,"user");

        $this->set(array(
            "memcached" => $tab
        ));
    }

    function genereCache()
    {
        for ($i = 0; $i < 100; $i++) {
            $login = \model\simple\String::random(5);
            \core\Memcached::value($login, "user", \model\simple\String::random(105, true), 60 * 60);
        }

    }

    function xmlrpcrxmlrpcrequestall()
    {
        $cmds = array(
            "d.get_hash=", "d.is_open=", "d.is_hash_checking=", "d.is_hash_checked=", "d.get_state=",
            "d.get_name=", "d.get_size_bytes=", "d.get_completed_chunks=", "d.get_size_chunks=", "d.get_bytes_done=",
            "d.get_up_total=", "d.get_ratio=", "d.get_up_rate=", "d.get_down_rate=", "d.get_chunk_size=",
            "d.get_custom1=", "d.get_peers_accounted=", "d.get_peers_not_connected=", "d.get_peers_connected=", "d.get_peers_complete=",
            "d.get_left_bytes=", "d.get_priority=", "d.get_state_changed=", "d.get_skip_total=", "d.get_hashing=",
            "d.get_chunks_hashed=", "d.get_base_path=", "d.get_creation_date=", "d.get_tracker_focus=", "d.is_active=",
            "d.get_message=", "d.get_custom2=", "d.get_free_diskspace=", "d.is_private=", "d.is_multi_file=", "d.get_throttle_name=", "d.get_custom=chk-state",
            "d.get_custom=chk-time", "d.get_custom=sch_ignore", 'cat="$t.multicall=d.get_hash=,t.get_scrape_complete=,cat={#}"', 'cat="$t.multicall=d.get_hash=,t.get_scrape_incomplete=,cat={#}"',
            'cat=$d.views=', "d.get_custom=seedingtime", "d.get_custom=addtime"
        );
        $cmd = new \model\xmlrpc\rXMLRPCCommand("d.multicall", "main");
        $cmd->addParameters(array_map("\\model\\xmlrpc\\rTorrentSettings::getCmd", $cmds));
        $cnt = count($cmd->params) - 1;
        $req = new \model\xmlrpc\rXMLRPCRequest(5001, $cmd);
        $t = null;
        if ($req->success()) {
            $i = 0;
            $tmp = array();
            $status = array('started' => 1, 'paused' => 2, 'checking' => 4, 'hashing' => 8, 'error' => 16);

            while ($i < count($req->val)) {
                $torrent = null;
                $state = 0;
                $is_open = $req->val[$i + 1];
                $is_hash_checking = $req->val[$i + 2];
                $is_hash_checked = $req->val[$i + 3];
                $get_state = $req->val[$i + 4];
                $get_hashing = $req->val[$i + 24];
                $is_active = $req->val[$i + 29];
                $msg = $req->val[$i + 30];
                if ($is_open != 0) {
                    $state |= $status["started"];
                    if (($get_state == 0) || ($is_active == 0))
                        $state |= $status["paused"];
                }
                if ($get_hashing != 0)
                    $state |= $status["hashing"];
                if ($is_hash_checking != 0)
                    $state |= $status["checking"];
                if ($msg != "" && $msg != "Tracker: [Tried all trackers.]")
                    $state |= $status["error"];
                $torrent[] = $state; //state 0
                $torrent[] = $req->val[$i + 5]; //nom 1
                $torrent[] = $req->val[$i + 6]; //taille 2
                $get_completed_chunks = $req->val[$i + 7];
                $get_hashed_chunks = $req->val[$i + 25];
                $get_size_chunks = $req->val[$i + 8];
                $chunks_processing = ($is_hash_checking == 0) ? $get_completed_chunks : $get_hashed_chunks;
                $done = floor($chunks_processing / $get_size_chunks * 1000);
                $torrent[] = $done; // 3
                $torrent[] = $req->val[$i + 9]; //downloaded 4
                $torrent[] = $req->val[$i + 10]; //Uploaded 5
                $torrent[] = $req->val[$i + 11]; //ratio 6
                $torrent[] = $req->val[$i + 12]; //UL 7
                $torrent[] = $req->val[$i + 13]; //DL 8
                $get_chunk_size = $req->val[$i + 14];
                $torrent[] = ($req->val[$i + 13] > 0 ? floor(($get_size_chunks - $get_completed_chunks) * $get_chunk_size / $req->val[$i + 13]) : -1); //Eta 9 (Temps restant en seconde)
                /*$get_peers_not_connected = $req->val[$i+17];
                $get_peers_connected = $req->val[$i+18];
                $get_peers_all = $get_peers_not_connected+$get_peers_connected;*/
                $torrent[] = $req->val[$i + 16]; //Peer Actual 10
                $torrent[] = $req->val[$i + 19]; //Seed Actual 11
                $seeds = 0;
                foreach (explode("#", $req->val[$i + 39]) as $k => $v) {
                    $seeds += $v;
                }
                $peers = 0;
                foreach (explode("#", $req->val[$i + 40]) as $k => $v) {
                    $peers += $v;
                }
                $torrent[] = $peers; //Peer total 12
                $torrent[] = $seeds; //Seed tota 13


                $torrent[] = $req->val[$i + 20]; //Taille restant 14
                $torrent[] = $req->val[$i + 21]; //Priority 15 (0 ne pas télécharger, 1 basse, 2 moyenne, 3 haute)
                $torrent[] = $req->val[$i + 22]; //State change 16 (dernière date de change d'état)
                $torrent[] = $req->val[$i + 23]; //Skip total 17
                $torrent[] = $req->val[$i + 26]; //Base Path 18
                $torrent[] = $req->val[$i + 27]; //Date create 19
                $torrent[] = $req->val[$i + 28]; //Focus tracker 20
                /*try {
                    torrent.comment = this.getValue(values,31);
                    if(torrent.comment.search("VRS24mrker")==0)
                        torrent.comment = decodeURIComponent(torrent.comment.substr(10));
                } catch(e) { torrent.comment = ''; }*/
                $torrent[] = $req->val[$i + 32]; //Torrent free diskspace 21
                $torrent[] = $req->val[$i + 33]; //Torrent is private 22
                $torrent[] = $req->val[$i + 34]; //Torrent is multifile 23
                $torrent[] = preg_replace("#\n#", "", $req->val[$i + 42]); //Torrent seed time 24
                $torrent[] = preg_replace("#\n#", "", $req->val[$i + 43]); //Torrent add time 25
                $torrent[] = $msg; //Message tracker 26
                $torrent[] = $req->val[$i]; //Hash 27
                $tmp[$req->val[$i]] = $torrent;
                $i = $i + 44;

            }
            $data = $tmp;
            /*if (isset($_REQUEST["cid"])){
                if ($anc = MyMemcache::value("listrt".$_REQUEST["cid"])){
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

            $cid = uniqid(sha1(time()).$_COOKIE["login"]);
            if (!(MyMemcache::value("listrt".$cid,$data,60*5)))
                trigger_error("Impossible de mettre des données dans le cache");*/
            $t[] = $tmp;
            //$t[]= $cid;
            //$t[]= $_SERVER["HTTP_HOST"];
            //$t[]= disk_total_space(Variable::$documentroot."../rtorrent/data/salorium");
            //$t[]= disk_total_space(Variable::$documentroot."../rtorrent/data/salorium")-disk_free_space(Variable::$documentroot."../rtorrent/data/salorium");

            $cmds = array(
                "get_up_rate", "get_upload_rate", "get_up_total", "get_down_rate", "get_download_rate", "get_down_total"
            );
            $req = new \model\xmlrpc\rXMLRPCRequest(5001);

            foreach ($cmds as $cmd)
                $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(5001, $cmd));
            if ($req->success())
                $t[] = $req->val;

        }
        if (is_null($t)) trigger_error("Impossible de se connecter à rtorrent :(");
        var_dump($t);
    }

    function ssh()
    {
        \model\simple\Ssh::supprime("salorium", "/home/salorium/test");
    }

} 