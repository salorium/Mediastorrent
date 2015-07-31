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
use model\mysql\Torrentfilm;
use model\mysql\Torrentserie;
use model\xmlrpc\rXMLRPCCommand;
use model\xmlrpc\rXMLRPCRequest;


class Torrent extends Controller
{
    function lvm()
    {
        $this->set("lvm", !is_null(\config\Conf::$nomvg));
    }

    function getListeFile($hashtorrentselectionne, $keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $cmds = array(
            "f.path=", "f.completed_chunks=", "f.size_chunks=", "f.size_bytes=", "f.priority=", "f.prioritize_first=", "f.prioritize_last="
        );
        $cmd = new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "f.multicall", array($hashtorrentselectionne, ""));

        foreach ($cmds as $prm) {
            $cmd->addParameter(\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, $prm));
        }
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi, new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.name", array($hashtorrentselectionne, "")));
        $req->addCommand($cmd);
        $files = null;
        $tmp = null;
        if (!$req->success()) {
            trigger_error("Impossible de récupéré la liste des fichiers de " . $hashtorrentselectionne);
            $files = $req->val;
        } else {
            $taille = count($req->val);
            $j = 0;
            $this->set("nom", $req->val[0]);
            for ($i = 1; $i < $taille; $i += 7) {
                $files[] = array($j, $req->val[$i], $req->val[$i + 1], $req->val[$i + 2], $req->val[$i + 3], $req->val[$i + 4], $req->val[$i + 5], $req->val[$i + 6]);
                $j++;
            }
            $tmp = $files;
        }
        $this->set(array(
            //"rpc" => rXMLRPCRequest::$query,
            "files" => $tmp,

            "hashtorrent" => $hashtorrentselectionne,
            "host" => HOST,
            //"seedbox" => \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }

    function getcreate($taskNo, $keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $to = \core\Memcached::value(\config\Conf::$user["user"]->login, "torrentfile" . $taskNo);
        $tott = new \model\simple\Torrent($to);
        $tott->send();
    }

    function checkcreate($taskNo, $keyconnexion = null)
    {
        $ret = null;
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $dir = ROOT . DS . "cache" . DS . \config\Conf::$user["user"]->login . $taskNo;
        $c = 0;
        while ($c < 10) {
            if (is_file($dir . '/pid') && is_readable($dir . '/pid')) {
                break;
            }
            sleep(5);
            $c++;
        }

        if (is_file($dir . '/pid') && is_readable($dir . '/pid')) {
            $pid = trim(file_get_contents($dir . '/pid'));
            $status = -1;
            if (is_file($dir . '/status') && is_readable($dir . '/status'))
                $status = trim(file_get_contents($dir . '/status'));
            $log = array();
            if (is_file($dir . '/log') && is_readable($dir . '/log')) {
                $lines = file($dir . '/log');
                foreach ($lines as $line) {
                    $pos = strrpos($line, "\r");
                    if ($pos !== false) {
                        $line = rtrim(substr($line, $pos + 1));
                        if (strlen($line) == 0)
                            continue;
                    }
                    if (strrpos($line, chr(8)) !== false) {
                        $len = strlen($line);
                        $res = array();
                        for ($i = 0; $i < $len; $i++) {
                            if ($line[$i] == chr(8))
                                array_pop($res);
                            else
                                $res[] = $line[$i];
                        }
                        $line = implode('', $res);
                    }
                    $log[] = rtrim($line);
                }
            }
            /*if(count($log)>MAX_CONSOLE_SIZE)
                array_splice($log,0,count($log)-MAX_CONSOLE_SIZE);
*/
            $errors = array();
            if (is_file($dir . '/errors') && is_readable($dir . '/errors'))
                $errors = array_map('trim', file($dir . '/errors'));
            $out = '';
            if (is_file($dir . '/out') && is_readable($dir . '/out'))
                $out = trim(file_get_contents($dir . '/out'));
            if ($status >= 0) {
                $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi,
                    new rXMLRPCCommand(\config\Conf::$userscgi, "execute2", array("", "rm", "-fr", $dir)));
                $req->run();
            }
            $ret = array(
                "no" => intval($taskNo),
                "status" => $status,
                "pid" => intval($pid),
                "out" => $out,
                "log" => $log,
                "errors" => $errors);
        }
        $this->set("res", $ret);
    }

    function create($keyconnexion = null)
    {
        $ret = null;
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $taskNo = time();
        \core\Memcached::value(\config\Conf::$user["user"]->login, "task" . $taskNo, serialize($_REQUEST), 60 * 1);
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi,
            new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "execute2", array("",
                "sh", "-c",
                escapeshellarg(ROOT . DS . "script" . DS . 'createtorrent.sh') . " " .
                $taskNo . " " .
                escapeshellarg("php") . " " .
                escapeshellarg(\config\Conf::$user["user"]->login) . " " .
                // escapeshellarg(\config\Conf::$userscgi) . " " .
                escapeshellarg(ROOT . DS . "cache" . DS) . " &")));
        if ($req->success())
            $ret = array("no" => intval($taskNo), "errors" => array(), "status" => -1, "out" => "");
        $this->set("res", $ret);
    }

    function liste($keyconnexion = null, $cid = null, $hashtorrentselectionne = "")
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        $tor = null;
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $cmds = array(
            "d.hash=" /*0*/, "d.is_open=" /*1*/, "d.is_hash_checking=" /*2*/, "d.is_hash_checked=" /*3*/, "d.state=" /*4*/,
            "d.name=" /*5*/, "d.size_bytes=" /*6*/, "d.completed_chunks=" /*7*/, "d.size_chunks=" /*8*/, "d.bytes_done=" /*9*/,
            "d.up.total=" /*10*/, "d.ratio=" /*11*/, "d.up.rate=" /*12*/, "d.down.rate=" /*13*/, "d.chunk_size=" /*14*/,
            "d.custom1=" /*15 A supprimer*/, "d.peers_accounted=" /*16*/, "d.peers_not_connected=" /*17*/, "d.peers_connected=" /*18*/, "d.peers_complete=" /*19*/,
            "d.left_bytes=" /*20*/, "d.priority=" /*21*/, "d.state_changed=" /*22*/, "d.skip.total=" /*23*/, "d.hashing=" /*24*/,
            "d.chunks_hashed=" /*25*/, "d.base_path=" /*26*/, "d.creation_date=" /*27*/, "d.tracker_focus=" /*28*/, "d.is_active=" /*29*/,
            "d.message=" /*30*/, "d.custom2=" /*31 A supprimer*/, "d.free_diskspace=" /*32*/, "d.is_private=" /*33*/, "d.is_multi_file=" /*34*/, "d.throttle_name=" /*35*/, "d.custom=chk-state" /*36*/,
            "d.custom=chk-time" /*37*/, "d.custom=sch_ignore" /*38*/, 'cat="$t.multicall=d.hash=,t.scrape_complete=,cat={#}"' /*39*/, 'cat="$t.multicall=d.hash=,t.scrape_incomplete=,cat={#}"' /*40*/,
            'cat=$d.views=' /*41*/, "d.timestamp.finished=" /*42*/, "d.timestamp.started=" /*43*/, "d.custom=clefunique", "d.custom=typemedias"
        );
        $cmd = new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.multicall2", array("", "main"));
        $res = array();
        foreach ($cmds as $v) {
            $res[] = \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, $v);
        }
        $cmd->addParameters($res);
        $cnt = count($cmd->params) - 1;
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi, $cmd);
        $t = null;
        Debug::startTimer("rtorrent");
        /*$req->success();
        Debug::endTimer("rtorrent");
        $this->set(array(
            //"torrent"=>$req->val,
            "tt"=> $req->vals
        ));
        return true;//*/
        if ($req->success(false)) {
            Debug::endTimer("rtorrent");
            $i = 0;
            $tmp = array();
            $status = array('started' => 1, 'paused' => 2, 'checking' => 4, 'hashing' => 8, 'error' => 16);
            $i = preg_match_all("/<array><data>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>.*<\/data><\/array>/Us", $req->val, $tmp1);
            for ($ii = 0; $ii < $i; $ii++) {
                $torrent = null;
                $state = 0;
                $is_open = $tmp1[2 + 4 * 1][$ii];
                $is_hash_checking = $tmp1[2 + 4 * 2][$ii];
                $is_hash_checked = $tmp1[2 + 4 * 3][$ii];
                $get_state = $tmp1[2 + 4 * 4][$ii];
                $get_hashing = $tmp1[2 + 4 * 24][$ii];
                $is_active = $tmp1[2 + 4 * 29][$ii];
                $msg = $tmp1[2 + 4 * 30][$ii];
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
                $torrent[] = $tmp1[2 + 4 * 5][$ii]; //nom 1
                $torrent[] = intval($tmp1[2 + 4 * 6][$ii]); //taille 2
                $get_completed_chunks = $tmp1[2 + 4 * 7][$ii];
                $get_hashed_chunks = $tmp1[2 + 4 * 25][$ii];
                $get_size_chunks = $tmp1[2 + 4 * 8][$ii];
                $chunks_processing = ($is_hash_checking == 0) ? $get_completed_chunks : $get_hashed_chunks;
                $done = floor($chunks_processing / $get_size_chunks * 1000);
                $torrent[] = $done; // 3
                $torrent[] = intval($tmp1[2 + 4 * 9][$ii]); //downloaded 4
                $torrent[] = intval($tmp1[2 + 4 * 10][$ii]); //Uploaded 5
                $torrent[] = intval($tmp1[2 + 4 * 11][$ii]); //ratio 6
                $torrent[] = intval($tmp1[2 + 4 * 12][$ii]); //UL 7
                $torrent[] = intval($tmp1[2 + 4 * 13][$ii]); //DL 8
                $get_chunk_size = $tmp1[2 + 4 * 14][$ii];
                $torrent[] = ($tmp1[2 + 4 * 13][$ii] > 0 ? floor(($get_size_chunks - $get_completed_chunks) * $get_chunk_size / $tmp1[2 + 4 * 13][$ii]) : -1); //Eta 9 (Temps restant en seconde)
                /*$get_peers_not_connected = $tmp1[2+4*17][$ii];
                $get_peers_connected = $tmp1[2+4*18][$ii];
                $get_peers_all = $get_peers_not_connected+$get_peers_connected;*/
                $torrent[] = intval($tmp1[2 + 4 * 16][$ii]); //Peer Actual 10
                $torrent[] = intval($tmp1[2 + 4 * 19][$ii]); //Seed Actual 11
                $seeds = 0;
                foreach (explode("#", $tmp1[2 + 4 * 39][$ii]) as $k => $v) {
                    $seeds += $v;
                }
                $peers = 0;
                foreach (explode("#", $tmp1[2 + 4 * 40][$ii]) as $k => $v) {
                    $peers += $v;
                }
                $torrent[] = $peers; //Peer total 12
                $torrent[] = $seeds; //Seed tota 13


                $torrent[] = intval($tmp1[2 + 4 * 20][$ii]); //Taille restant 14
                $torrent[] = intval($tmp1[2 + 4 * 21][$ii]); //Priority 15 (0 ne pas télécharger, 1 basse, 2 moyenne, 3 haute)
                $torrent[] = intval($tmp1[2 + 4 * 22][$ii]); //State change 16 (dernière date de change d'état)
                $torrent[] = intval($tmp1[2 + 4 * 23][$ii]); //Skip total Contiens les rejets en mo 17
                $torrent[] = $tmp1[2 + 4 * 26][$ii]; //Base Path 18
                $torrent[] = intval($tmp1[2 + 4 * 27][$ii]); //Date create 19
                $torrent[] = intval($tmp1[2 + 4 * 28][$ii]); //Focus tracker 20
                /*try {
                    torrent.comment = this.getValue(values,31);
                    if(torrent.comment.search("VRS24mrker")==0)
                        torrent.comment = decodeURIComponent(torrent.comment.substr(10));
                } catch(e) { torrent.comment = ''; }*/
                $torrent[] = intval($tmp1[2 + 4 * 32][$ii]); //Torrent free diskspace 21
                $torrent[] = intval($tmp1[2 + 4 * 33][$ii]); //Torrent is private 22
                $torrent[] = intval($tmp1[2 + 4 * 34][$ii]); //Torrent is multifile 23
                $torrent[] = /*preg_replace("#\n#", "", */
                    intval($tmp1[2 + 4 * 42][$ii]); //Torrent seed time 24
                $torrent[] = /*preg_replace("#\n#", "", */
                    intval($tmp1[2 + 4 * 43][$ii]); //Torrent add time 25
                $torrent[] = $msg; //Message tracker 26
                $torrent[] = $tmp1[2 + 4 * 0][$ii]; //Hash 27
                $torrent[] = $tmp1[2 + 4 * 44][$ii]; //Clefunique
                $torrent[] = $tmp1[2 + 4 * 45][$ii]; //Type medias
                if ($hashtorrentselectionne == $tmp1[2 + 4 * 0][$ii])
                    $tor = $torrent;
                $tmp[$tmp1[2 + 4 * 0][$ii]] = $torrent;
            }
            $data = $tmp;
            if (!is_null($cid)) {
                if ($anc = \core\Memcached::value("torrentlist" . \config\Conf::$userscgi, $cid)) {
                    foreach ($anc as $k => $v) {
                        if (!isset($tmp[$k]))
                            $tmp[$k] = false;
                        foreach ($v as $kk => $vv) {
                            if (isset($tmp[$k][$kk]) && $tmp[$k][$kk] == $vv) {
                                unset($tmp[$k][$kk]);
                            }
                        }
                        if (count($tmp[$k]) == 0)
                            unset($tmp[$k]);
                    }
                }
            }

            $ncid = \model\simple\String::random(5);
            \core\Memcached::del("torrentlist" . \config\Conf::$userscgi, $cid);
            if (!(\core\Memcached::value("torrentlist" . \config\Conf::$userscgi, $ncid, $data, 60 * 5)))
                trigger_error("Impossible de mettre des données dans le cache");
            $t[] = $tmp;
            $t[] = $ncid;
            $path = DS . "home" . DS . \config\Conf::$user["user"]->login . DS . "rtorrent" . DS . "data";
            $t[] = disk_total_space($path) - disk_free_space($path);
            $t[] = disk_total_space($path);


            $cmds = array(
                "throttle.global_up.rate", "throttle.global_up.max_rate", "throttle.global_up.total", "throttle.global_down.rate", "throttle.global_down.max_rate", "throttle.global_down.total"
            );
            $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi);

            foreach ($cmds as $cmd)
                $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, $cmd));
            if ($req->success())
                $t[] = $req->val;

        }
        if (is_null($t)) trigger_error("Impossible de se connecter à rtorrent :(");
        $torrent = null;
        if ($hashtorrentselectionne !== "") {
            /*
             * =================================================
             * Détails du torrent hashtorrent
             * =================================================
             */
            $tmp = $tor;
            $data = $tmp;
            if (!is_null($cid)) {
                if ($anc = \core\Memcached::value("detaillist" . \config\Conf::$userscgi, sha1($cid . $hashtorrentselectionne))) {
                    foreach ($anc as $k => $v) {
                        if (isset($tmp[$k]) && $tmp[$k] == $v) {
                            unset($tmp[$k]);
                        }
                    }
                }
            }
            \core\Memcached::del("detaillist" . \config\Conf::$userscgi, sha1($cid . $hashtorrentselectionne));
            if (!(\core\Memcached::value("detaillist" . \config\Conf::$userscgi, sha1($ncid . $hashtorrentselectionne), $data, 60 * 5))) {
                trigger_error("Impossible de mettre des données dans le cache");
            }
            $torrent["detail"] = $tmp;
            /*
             * =================================================
             * Détails du torrent hashtorrent (file liste)
             * =================================================
             */
            $cmds = array(
                "f.path=", "f.completed_chunks=", "f.size_chunks=", "f.size_bytes=", "f.priority=", "f.prioritize_first=", "f.prioritize_last="
            );
            $cmd = new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "f.multicall", array($hashtorrentselectionne, ""));

            foreach ($cmds as $prm) {
                $cmd->addParameter(\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, $prm));
            }
            $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi, $cmd);
            $files = null;
            $to = null;
            if (!$req->success()) {
                trigger_error("Impossible de récupéré la liste des fichiers de " . $hashtorrentselectionne);
                $files = $req->val;
            } else {
                $taille = count($req->val);
                $j = 0;
                for ($i = 0; $i < $taille; $i += 7) {
                    $files[] = array($j, $req->val[$i], $req->val[$i + 1], $req->val[$i + 2], $req->val[$i + 3], $req->val[$i + 4], $req->val[$i + 5], $req->val[$i + 6]);
                    $j++;
                }
                $tmp = $files;
                $data = $tmp;
                if (!is_null($cid)) {
                    if ($anc = \core\Memcached::value("fileslist" . \config\Conf::$userscgi, sha1($cid . $hashtorrentselectionne))) {
                        foreach ($anc as $k => $v) {
                            if (!isset($tmp[$k]))
                                $tmp[$k] = false;
                            foreach ($v as $kk => $vv) {
                                if (isset($tmp[$k][$kk]) && $tmp[$k][$kk] == $vv) {
                                    unset($tmp[$k][$kk]);
                                }
                            }
                            if (count($tmp[$k]) == 0)
                                unset($tmp[$k]);
                        }
                    }
                }
                \core\Memcached::del("fileslist" . \config\Conf::$userscgi, sha1($cid . $hashtorrentselectionne));
                if (!(\core\Memcached::value("fileslist" . \config\Conf::$userscgi, sha1($ncid . $hashtorrentselectionne), $data, 60 * 5)))
                    trigger_error("Impossible de mettre des données dans le cache");
                $torrent["files"] = $tmp;
            }
            /*
             * =================================================
             * Détails du torrent hashtorrent (traker liste)
             * =================================================
             */
            $cmds = array(
                "t.url=", "t.type=", "t.is_enabled=", "t.group=", "t.scrape_complete=",
                "t.scrape_incomplete=", "t.scrape_downloaded=",
                "t.normal_interval=", "t.scrape_time_last="
            );
            $cmd = new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "t.multicall", array($hashtorrentselectionne, ""));

            foreach ($cmds as $prm) {
                $cmd->addParameter(\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, $prm));
            }
            $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi, $cmd);
            $trackers = null;
            if (!$req->success()) {
                trigger_error("Impossible de récupéré la liste des trakers de " . $hashtorrentselectionne);
                $traker = $req->val;
            } else {

                $taille = count($req->val);
                $j = 0;
                for ($i = 0; $i < $taille; $i += 9) {
                    $trackers[] = array($j, $req->val[$i], $req->val[$i + 1], $req->val[$i + 2], $req->val[$i + 3], $req->val[$i + 4], $req->val[$i + 5], $req->val[$i + 6], $req->val[$i + 7], $req->val[$i + 8]);
                    $j++;
                }
                /*for ($i = 0; $i < 30; $i++)
                    $trackers[] = $trackers[0];
                    /*$tmp = $files;
                    $data = $tmp;
                    if (!is_null($cid)) {
                        if ($anc = \core\Memcached::value("fileslist" . \config\Conf::$userscgi, sha1($cid . $hashtorrentselectionne))) {
                            foreach ($anc as $k => $v) {
                                if (!isset($tmp[$k]))
                                    $tmp[$k] = false;
                                foreach ($v as $kk => $vv) {
                                    if (isset($tmp[$k][$kk]) && $tmp[$k][$kk] == $vv) {
                                        unset($tmp[$k][$kk]);
                                    }
                                }
                                if (count($tmp[$k]) == 0)
                                    unset($tmp[$k]);
                            }
                        }
                    }

                    if (!(\core\Memcached::value("fileslist" . \config\Conf::$userscgi, sha1($ncid . $hashtorrentselectionne), $data, 60 * 5)))
                        trigger_error("Impossible de mettre des données dans le cache");
                    */
                $torrent["trackers"] = $trackers;
            }

        }

        $this->set(array(
            "torrent" => $t,
            "torrentselectionnee" => $torrent,
            "hashtorrent" => $hashtorrentselectionne,
            "host" => HOST,
            //"seedbox" => \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }

    function pause($keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $cmds = array(
            "d.stop"
        );

        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi);
        foreach ($_REQUEST["hash"] as $h)
            foreach ($cmds as $cmd)
                $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, $cmd, $h));
        $r = ($req->success() ? $req->val : false);

        $this->set(array(
            "rtorrent" => $r,
            //"seedbox" => \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }

    function start($keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $cmds = array("d.open", "d.start");

        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi);
        foreach ($_REQUEST["hash"] as $h)
            foreach ($cmds as $cmd)
                $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, $cmd, $h));
        $r = ($req->success() ? $req->val : false);

        $this->set(array(
            "rtorrent" => $r,
            "seedbox" => \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }

    function stop($keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $cmds = array("d.stop", "d.close");

        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi);
        foreach ($_REQUEST["hash"] as $h)
            foreach ($cmds as $cmd)
                $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, $cmd, $h));
        $r = ($req->success() ? $req->val : false);

        $this->set(array(
            "rtorrent" => $r,
            "seedbox" => \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }

    function recheck($keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $cmds = array("d.check_hash");

        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi);
        foreach ($_REQUEST["hash"] as $h)
            foreach ($cmds as $cmd)
                $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, $cmd, $h));

        $r = ($req->success() ? $req->val : false);

        $this->set(array(
            "rtorrent" => $r,
            "seedbox" => \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }

    function delete($keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $cmds = array("d.erase");

        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi);
        foreach ($_REQUEST["hash"] as $h)
            foreach ($cmds as $cmd)
                $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, $cmd, $h));
        $r = ($req->success() ? $req->val : false);

        $this->set(array(
            "rtorrent" => $r,
            "seedbox" => \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }

    function deleteall($keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");

        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi);
        foreach ($_REQUEST["hash"] as $h) {
            $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.name", $h));
            //$req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.get_name", $h));
            $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.custom1.set", array($h, "1")));
            $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.custom", array($h, "clefunique")));
            $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.custom", array($h, "typemedias")));
            $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.erase", $h));
        }

        $r = ($req->success() ? $req->val : $req->val);
        $taille = count($r);
        $d = array();
        for ($i = 0; $i < $taille; $i += 5) {
            if ($r[$i + 3] !== "aucun") {
                switch ($r[$i + 3]) {
                    case "film":
                        Torrentfilm::deleteByClefunique($r[$i + 2]);
                        break;
                    case "serie":
                        Torrentserie::deleteByClefunique($r[$i + 2]);
                        break;
                }
            }
            if ($r[$i] !== "" && $r[$i + 1] == 1 && $r[$i + 4] == 0) {
                $d[$r[$i]] = true;
            } else {
                $d[$r[$i]] = false;
            }
        }
        $this->set(array(
            "torrent" => $d,
            "seedbox" => \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));

    }

    function senda($keyconnexion = null)
    {
        /*
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $erreur = 1;
        $torrents = null;
        $clefunique = null;
        $typemedias = null;
        if (isset($_REQUEST["mediastorrent"])) {
            $tmpclefunique = null;
            for ($idtorrent = 0; $idtorrent < $_REQUEST["nbtorrents"]; $idtorrent++) {
                if (isset($_REQUEST["torrent" . $idtorrent . "addbibli"])) {
                    $typemedias[$_REQUEST["torrent" . $idtorrent . "hash"]] = $_REQUEST["torrent" . $idtorrent . "type"];
                    switch ($_REQUEST["torrent" . $idtorrent . "type"]) {
                        case 'film':
                            if ($_REQUEST["torrent" . $idtorrent . "recherche"] === "manuel") {
                                //Manuel
                                $titre = trim($_REQUEST["torrent" . $idtorrent . "detailstitre"]);
                                $otitre = trim($_REQUEST["torrent" . $idtorrent . "detailstitreoriginal"]);
                                $synopsis = trim($_REQUEST["torrent" . $idtorrent . "detailssynopsis"]);
                                $genre = explode(",", $_REQUEST["torrent" . $idtorrent . "detailsgenre"]);
                                array_walk($genre, create_function('&$val', '$val = trim($val);'));
                                array_walk($genre, create_function('&$val', '$val = strtolower($val);'));
                                array_walk($genre, create_function('&$val', '$val = ucfirst($val);'));
                                $acteurs = explode(",", $_REQUEST["torrent" . $idtorrent . "detailsacteur"]);
                                array_walk($acteurs, create_function('&$val', '$val = trim($val);'));
                                array_walk($acteurs, create_function('&$val', '$val = strtolower($val);'));
                                array_walk($acteurs, create_function('&$val', '$val = ucwords($val);'));
                                $acteurs = implode(", ", $acteurs);
                                $realisateurs = explode(",", $_REQUEST["torrent" . $idtorrent . "detailsrealisateur"]);
                                array_walk($realisateurs, create_function('&$val', '$val = trim($val);'));
                                array_walk($realisateurs, create_function('&$val', '$val = strtolower($val);'));
                                array_walk($realisateurs, create_function('&$val', '$val = ucwords($val);'));
                                $realisateurs = implode(", ", $realisateurs);
                                $anneeprod = trim($_REQUEST["torrent" . $idtorrent . "detailsanneeprod"]);
                                $urlposter = trim($_REQUEST["torrent" . $idtorrent . "detailsposter"]);
                                $urlbackdrop = trim($_REQUEST["torrent" . $idtorrent . "detailsbackdrop"]);
                                $infos["Titre"] = $titre;
                                $infos["Titre original"] = $otitre;
                                $infos["Genre"] = implode(", ", $genre);
                                $infos["Réalisateur(s)"] = $realisateurs;
                                $infos["Acteur(s)"] = $acteurs;
                                $infos["Année de production"] = $anneeprod;
                                $infos["Synopsis"] = $synopsis;
                                $film = \model\mysql\Film::ajouteFilm($titre, $otitre, json_encode($infos), $urlposter, $urlbackdrop, $anneeprod, $acteurs, $realisateurs);
                                $idfilm = $film->id;
                                $film->addGenre($genre);
                            } else {
                                //Auto
                                if ($_REQUEST["torrent" . $idtorrent . "typerecherche"] === "local") {
                                    //Local
                                    $idfilm = $_REQUEST["torrent" . $idtorrent . "code"];
                                } else {
                                    //Allo
                                    $o["typesearch"] = "movie";
                                    $allo = new \model\simple\Allocine($_REQUEST["torrent" . $idtorrent . "code"], $o);
                                    $infos = $allo->retourneResMovieFormatForBD();
                                    $genre = $infos["Genre"];
                                    $infos["Genre"] = implode(", ", $genre);
                                    $titre = (isset($infos["Titre"]) ? $infos["Titre"] : $infos["Titre original"]);
                                    $otitre = $infos["Titre original"];
                                    $urlposter = trim($_REQUEST["torrent" . $idtorrent . "detailsposter"]);
                                    $urlbackdrop = trim($_REQUEST["torrent" . $idtorrent . "detailsbackdrop"]);
                                    $realisateurs = $infos["Réalisateur(s)"];
                                    $acteurs = "";
                                    if (isset($infos["Acteur(s)"]))
                                        $acteurs = $infos["Acteur(s)"];
                                    $anneeprod = $infos["Année de production"];
                                    $film = \model\mysql\Film::ajouteFilm($titre, $otitre, json_encode($infos), $urlposter, $urlbackdrop, $anneeprod, $acteurs, $realisateurs, $_REQUEST["torrent" . $idtorrent . "code"]);
                                    $idfilm = $film->id;
                                    $film->addGenre($genre);
                                }
                            }
                            $clef = \model\mysql\Torrentfilm::getClefUnique();
                            $clefunique[$_REQUEST["torrent" . $idtorrent . "hash"]] = $clef;
                            for ($idfile = 0; $idfile < $_REQUEST["torrent" . $idtorrent . "nbfiles"]; $idfile++) {
                                if (isset($_REQUEST["torrent" . $idtorrent . "ajoutecheckfile" . $idfile])) {
                                    \model\mysql\Torrentfilm::addTorrentFilm($idfilm, $_REQUEST["torrent" . $idtorrent . "numfile" . $idfile], $_REQUEST["torrent" . $idtorrent . "filecomplement" . $idfile], \config\Conf::$user["user"]->login, \config\Conf::$nomrtorrent, $_REQUEST["torrent" . $idtorrent . "hash"], $clef, (isset($_REQUEST["torrent" . $idtorrent . "partagecheckfile" . $idfile])));
                                }
                            }
                            break;
                    }
                }
            }
        }
        if (isset ($_FILES ['torrentfile'])) {
            if (is_array($_FILES['torrentfile']['name'])) {

                for ($i = 0; $i < count($_FILES['torrentfile']['name']); ++$i) {
                    $files[] = array
                    (
                        'name' => $_FILES['torrentfile']['name'][$i],
                        'tmp_name' => $_FILES['torrentfile']['tmp_name'][$i],
                        'error' => $_FILES ['torrentfile'] ['error'][$i]
                    );
                }

            } else {
                $files[] = $_FILES['torrentfile'];

            }


            foreach ($files as $file) {
                $erreur = 0;
                $torrent = null;
                $torrent['erreur'] = 1;
                $torrent['nom'] = $file["name"];
                if (pathinfo($file["name"], PATHINFO_EXTENSION) != "torrent")
                    $file["name"] .= ".torrent";
                $des = DS . "tmp" . DS . $file["name"];
                $torrent['nom'] = $file["name"];
                $ok = move_uploaded_file($file['tmp_name'], $des);
                if ($ok) {
                    $to = new \model\simple\Torrent($des);

                    //$torrents[]= array($to->getFileName(),$to->info["name"]);
                    if ($to->errors()) {
                        $torrent['status'] = "Erreur du fichier torrent";
                    } else {
                        $torrent['erreur'] = 0;
                        $torrent["status"] = \model\xmlrpc\rTorrent::sendTorrent($to, !isset($_REQUEST['autostart']));
                        $torrent["clefunique"] = \model\simple\String::random(10);
                        usleep(40000);
                        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi, array(
                            new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.set_custom", array($to->hash_info(), "clefunique", $clefunique[$to->hash_info()])),
                            new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.set_custom", array($to->hash_info(), "typemedias", (isset($typemedias[$to->hash_info()]) ? $typemedias[$to->hash_info()] : "aucun")))));
                        $torrent["clefuniqueres"] = ($req->success() ? $req->val : $req->val);

                    }
                    unlink($des);
                } else {
                    $torrent['status'] = "Erreur lors de l'upload | Code d'erreur => " . $file["error"];
                }
                $torrents[] = $torrent;
            }
        } else {
            $status = "Pas de fichier envoyer";
        }
        */
        $this->set(array(
            //"torrent" => $torrents,
            //"erreur" => $erreur,
            //"status" => $status,

            "post" => $_REQUEST,
            //  "seedbox" => \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }


    function send($keyconnexion = null)
    {

        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $erreur = 1;
        $torrents = null;
        $clefunique = null;
        $typemedias = null;
        $status="ok";
        /*
         * =================================================
         * Traitement Mediastorrent !!!!
         * =================================================
         */
        if (isset($_REQUEST["mediastorrent"])) {
            $tmpclefunique = null;
            for ($idtorrent = 0; $idtorrent < $_REQUEST["nbtorrents"]; $idtorrent++) {
                if (isset($_REQUEST["torrent" . $idtorrent . "addbibli"])) {
                    $typemedias[$_REQUEST["torrent" . $idtorrent . "hash"]] = $_REQUEST["torrent" . $idtorrent . "type"];
                    switch ($_REQUEST["torrent" . $idtorrent . "type"]) {
                        case 'film':
                            $clef = \model\mysql\Torrentfilm::getClefUnique();
                            $clefunique[$_REQUEST["torrent" . $idtorrent . "hash"]] = $clef;
                            for ($idfile = 0; $idfile < $_REQUEST["torrent" . $idtorrent . "nbfiles"]; $idfile++) {
                                if (isset($_REQUEST["torrent" . $idtorrent . "ajoutecheckfile" . $idfile]) && isset($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "recherche"])) {
                                    if ($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "recherche"] === "manuel") {
                                        //Manuel
                                        $titre = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "titre"]);
                                        $otitre = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "titreoriginal"]);
                                        $synopsis = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "synopsis"]);
                                        $genre = explode(",", $_REQUEST["torrent" . $idtorrent . "file" . $idfile . "genre"]);
                                        array_walk($genre, create_function('&$val', '$val = trim($val);'));
                                        array_walk($genre, create_function('&$val', '$val = strtolower($val);'));
                                        array_walk($genre, create_function('&$val', '$val = ucfirst($val);'));
                                        $acteurs = explode(",", $_REQUEST["torrent" . $idtorrent . "file" . $idfile . "acteur"]);
                                        array_walk($acteurs, create_function('&$val', '$val = trim($val);'));
                                        array_walk($acteurs, create_function('&$val', '$val = strtolower($val);'));
                                        array_walk($acteurs, create_function('&$val', '$val = ucwords($val);'));
                                        $acteurs = implode(", ", $acteurs);
                                        $realisateurs = explode(",", $_REQUEST["torrent" . $idtorrent . "file" . $idfile . "realisateur"]);
                                        array_walk($realisateurs, create_function('&$val', '$val = trim($val);'));
                                        array_walk($realisateurs, create_function('&$val', '$val = strtolower($val);'));
                                        array_walk($realisateurs, create_function('&$val', '$val = ucwords($val);'));
                                        $realisateurs = implode(", ", $realisateurs);
                                        $anneeprod = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "anneeprod"]);
                                        $urlposter = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "poster"]);
                                        $urlbackdrop = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "backdrop"]);
                                        $infos["Titre"] = $titre;
                                        $infos["Titre original"] = $otitre;
                                        $infos["Genre"] = implode(", ", $genre);
                                        $infos["Réalisateur(s)"] = $realisateurs;
                                        $infos["Acteur(s)"] = $acteurs;
                                        $infos["Année de production"] = $anneeprod;
                                        $infos["Synopsis"] = $synopsis;
                                        $film = \model\mysql\Film::ajouteFilm($titre, $otitre, json_encode($infos), $urlposter, $urlbackdrop, $anneeprod, $acteurs, $realisateurs);
                                        $idfilm = $film->id;
                                        $film->addGenre($genre);
                                    } else {
                                        //Auto
                                        if ($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "typerecherche"] === "local") {
                                            //Local
                                            $idfilm = $_REQUEST["torrent" . $idtorrent . "file" . $idfile . "code"];
                                        } else {
                                            //Allo
                                            $o["typesearch"] = "movie";
                                            $allo = new \model\simple\Allocine($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "code"], $o);
                                            $infos = $allo->retourneResMovieFormatForBD();
                                            $genre = $infos["Genre"];
                                            $infos["Genre"] = implode(", ", $genre);
                                            $titre = (isset($infos["Titre"]) ? $infos["Titre"] : $infos["Titre original"]);
                                            $otitre = $infos["Titre original"];
                                            $urlposter = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "poster"]);
                                            $urlbackdrop = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "backdrop"]);
                                            $realisateurs = $infos["Réalisateur(s)"];
                                            $acteurs = "";
                                            if (isset($infos["Acteur(s)"]))
                                                $acteurs = $infos["Acteur(s)"];
                                            $anneeprod = $infos["Année de production"];
                                            $film = \model\mysql\Film::ajouteFilm($titre, $otitre, json_encode($infos), $urlposter, $urlbackdrop, $anneeprod, $acteurs, $realisateurs, $_REQUEST["torrent" . $idtorrent . "file" . $idfile . "code"]);
                                            $idfilm = $film->id;
                                            $film->addGenre($genre);
                                        }
                                    }
                                    \model\mysql\Torrentfilm::addTorrentFilm($idfilm, $_REQUEST["torrent" . $idtorrent . "numfile" . $idfile], $_REQUEST["torrent" . $idtorrent . "filecomplement" . $idfile], \config\Conf::$user["user"]->login, \config\Conf::$nomrtorrent, $_REQUEST["torrent" . $idtorrent . "hash"], $clef, (isset($_REQUEST["torrent" . $idtorrent . "partagecheckfile" . $idfile])));
                                }
                            }
                            break;
                        case 'serie':
                            $clef = \model\mysql\Torrentserie::getClefUnique();
                            $clefunique[$_REQUEST["torrent" . $idtorrent . "hash"]] = $clef;
                            for ($idfile = 0; $idfile < $_REQUEST["torrent" . $idtorrent . "nbfiles"]; $idfile++) {
                                if (isset($_REQUEST["torrent" . $idtorrent . "ajoutecheckfile" . $idfile]) && isset($_REQUEST["torrent" . $idtorrent . "filerecherche"])) {
                                    if ($_REQUEST["torrent" . $idtorrent . "filerecherche"] === "manuel") {
                                        //Manuel
                                        /**
                                         * Todo check $idfile, une série == un torrent, pareil pour les différents fichier le contenant différent du film
                                         */
                                        $titre = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "titre"]);
                                        $otitre = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "titreoriginal"]);
                                        $synopsis = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "synopsis"]);
                                        $genre = explode(",", $_REQUEST["torrent" . $idtorrent . "file" . $idfile . "genre"]);
                                        array_walk($genre, create_function('&$val', '$val = trim($val);'));
                                        array_walk($genre, create_function('&$val', '$val = strtolower($val);'));
                                        array_walk($genre, create_function('&$val', '$val = ucfirst($val);'));
                                        $acteurs = explode(",", $_REQUEST["torrent" . $idtorrent . "file" . $idfile . "acteur"]);
                                        array_walk($acteurs, create_function('&$val', '$val = trim($val);'));
                                        array_walk($acteurs, create_function('&$val', '$val = strtolower($val);'));
                                        array_walk($acteurs, create_function('&$val', '$val = ucwords($val);'));
                                        $acteurs = implode(", ", $acteurs);
                                        $realisateurs = explode(",", $_REQUEST["torrent" . $idtorrent . "file" . $idfile . "realisateur"]);
                                        array_walk($realisateurs, create_function('&$val', '$val = trim($val);'));
                                        array_walk($realisateurs, create_function('&$val', '$val = strtolower($val);'));
                                        array_walk($realisateurs, create_function('&$val', '$val = ucwords($val);'));
                                        $realisateurs = implode(", ", $realisateurs);
                                        $anneeprod = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "anneeprod"]);
                                        $urlposter = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "poster"]);
                                        $urlbackdrop = trim($_REQUEST["torrent" . $idtorrent . "file" . $idfile . "backdrop"]);
                                        $infos["Titre"] = $titre;
                                        $infos["Titre original"] = $otitre;
                                        $infos["Genre"] = implode(", ", $genre);
                                        $infos["Réalisateur(s)"] = $realisateurs;
                                        $infos["Acteur(s)"] = $acteurs;
                                        $infos["Année de production"] = $anneeprod;
                                        $infos["Synopsis"] = $synopsis;
                                        $serie = \model\mysql\Serie::ajouteSerie($titre, $otitre, json_encode($infos), $urlposter, $urlbackdrop, $anneeprod, $acteurs, $realisateurs);
                                        $idserie = $serie->id;
                                        $serie->addGenre($genre);
                                    } else {
                                        //Auto
                                        if ($_REQUEST["torrent" . $idtorrent . "filetyperecherche"] === "local") {
                                            //Local
                                            $idserie = $_REQUEST["torrent" . $idtorrent . "filecode"];
                                        } else {
                                            //Allo
                                            $o["typesearch"] = "tvseries";
                                            $allo = new \model\simple\Allocine($_REQUEST["torrent" . $idtorrent . "filecode"], $o);
                                            $infos = $allo->retourneResSerieFormatForBD();
                                            $genre = $infos["Genre"];
                                            $infos["Genre"] = implode(", ", $genre);
                                            $titre = (isset($infos["Titre"]) ? $infos["Titre"] : $infos["Titre original"]);
                                            $otitre = $infos["Titre original"];
                                            $urlposter = trim($_REQUEST["torrent" . $idtorrent . "fileposter"]);
                                            $urlbackdrop = trim($_REQUEST["torrent" . $idtorrent . "filebackdrop"]);
                                            $realisateurs = $infos["Réalisateur(s)"];
                                            $acteurs = "";
                                            if (isset($infos["Acteur(s)"]))
                                                $acteurs = $infos["Acteur(s)"];
                                            $anneeprod = $infos["Lancement"];
                                            $this->set("ICI", "Ok");
                                            $serie = \model\mysql\Serie::ajouteSerie($titre, $otitre, json_encode($infos), $urlposter, $urlbackdrop, $anneeprod, $acteurs, $realisateurs, $_REQUEST["torrent" . $idtorrent . "filecode"]);
                                            $idserie = $serie->id;
                                            $serie->addGenre($genre);
                                        }
                                    }
                                    \model\mysql\Torrentserie::addTorrentSerie($idserie, $_REQUEST["torrent" . $idtorrent . "numfile" . $idfile], $_REQUEST["torrent" . $idtorrent . "filecomplement" . $idfile], \config\Conf::$user["user"]->login, $_REQUEST["torrent" . $idtorrent . "filesaison" . $idfile], $_REQUEST["torrent" . $idtorrent . "fileepisode" . $idfile], \config\Conf::$nomrtorrent, $_REQUEST["torrent" . $idtorrent . "hash"], $clef, (isset($_REQUEST["torrent" . $idtorrent . "partagecheckfile" . $idfile])));
                                }
                            }
                            break;
                    }
                }
            }
        }

        if (isset ($_FILES ['torrentfile']) && !(count( $_FILES['torrentfile']) == 1 && $_FILES ['torrentfile'] ['error'][0] != 4)) {
            if (is_array($_FILES['torrentfile']['name'])) {

                for ($i = 0; $i < count($_FILES['torrentfile']['name']); ++$i) {
                    $files[] = array
                    (
                        'name' => $_FILES['torrentfile']['name'][$i],
                        'tmp_name' => $_FILES['torrentfile']['tmp_name'][$i],
                        'error' => $_FILES ['torrentfile'] ['error'][$i]
                    );
                }

            } else {
                $files[] = $_FILES['torrentfile'];

            }


            foreach ($files as $file) {
                $erreur = 0;
                $torrent = null;
                $torrent['erreur'] = 1;
                $torrent['nom'] = $file["name"];
                if (pathinfo($file["name"], PATHINFO_EXTENSION) != "torrent")
                    $file["name"] .= ".torrent";
                $des = DS . "tmp" . DS . $file["name"];
                $torrent['nom'] = $file["name"];
                $ok = move_uploaded_file($file['tmp_name'], $des);
                if ($ok) {
                    $to = new \model\simple\Torrent($des);

                    //$torrents[]= array($to->getFileName(),$to->info["name"]);
                    if ($to->errors()) {
                        $torrent['status'] = "Erreur du fichier torrent";
                    } else {
                        $torrent["status"] = \model\xmlrpc\rTorrent::sendTorrent($to, !isset($_REQUEST['autostart']),$_REQUEST['repertoire']);
                        $torrent["clefunique"] = \model\simple\String::random(10);
                        usleep(40000);
                        if ( $torrent['status'][0] === '0'){
                            $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi, array(
                                new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.custom.set", array($to->hash_info(), "clefunique", $clefunique[$to->hash_info()])),
                                new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.custom.set", array($to->hash_info(), "typemedias", (isset($typemedias[$to->hash_info()]) ? $typemedias[$to->hash_info()] : "aucun")))));
                        $torrent["clefuniqueres"] = ($req->success() ? $req->val : $req->val);
                            if ($torrent["clefuniqueres"][0] === "0" && $torrent["clefuniqueres"][1] === "0"  ){
                                $torrent['erreur'] = 0;
                            }
                        }
                    }
                    unlink($des);
                } else {
                    $torrent['status'] = "Erreur lors de l'upload | Code d'erreur => " . $file["error"];
                }
                $torrents[] = $torrent;
            }
        } else {
            $status = "Pas de fichier envoyer";
        }

        $this->set(array(
            "torrents" => $torrents,
            "erreur" => $erreur,
            "status" => $status,

//            "post" => $_REQUEST,
//            "FILE"=> $_FILES,
            "seedbox" => \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }

    function details($hashtorrentselectionne, $keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $cmds = array(
            "f.path=", "f.completed_chunks=", "f.size_chunks=", "f.size_bytes=", "f.priority=", "f.prioritize_first=", "f.prioritize_last="
        );
        $cmd = new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "f.multicall", array($hashtorrentselectionne, ""));

        foreach ($cmds as $prm) {
            $cmd->addParameter(\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, $prm));
        }
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi, $cmd);
        $files = null;
        $to = null;
        if (!$req->success()) {
            trigger_error("Impossible de récupéré la liste des fichiers de " . $hashtorrentselectionne);
            $files = $req->val;
        } else {
            $taille = count($req->val);
            $j = 0;
            for ($i = 0; $i < $taille; $i += 7) {
                $files[] = array($j, $req->val[$i], $req->val[$i + 1], $req->val[$i + 2], $req->val[$i + 3], $req->val[$i + 4], $req->val[$i + 5], $req->val[$i + 6]);
                $j++;
            }
            $to["files"] = $files;
        }
        $cmds = array(
            "t.url=", "t.type=", "t.is_enabled=", "t.group=", "t.scrape_complete=",
            "t.scrape_incomplete=", "t.scrape_downloaded=",
            "t.normal_interval=", "t.scrape_time_last="
        );
        $cmd = new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "t.multicall", array($hashtorrentselectionne, ""));

        foreach ($cmds as $prm) {
            $cmd->addParameter(\model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, $prm));
        }
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi, $cmd);
        $trackers = null;
        if (!$req->success()) {
            trigger_error("Impossible de récupéré la liste des trakers de " . $hashtorrentselectionne);
            $traker = $req->val;
        } else {

            $taille = count($req->val);
            $j = 0;
            for ($i = 0; $i < $taille; $i += 9) {
                $trackers[] = array($j, $req->val[$i], $req->val[$i + 1], $req->val[$i + 2], $req->val[$i + 3], $req->val[$i + 4], $req->val[$i + 5], $req->val[$i + 6], $req->val[$i + 7], $req->val[$i + 8]);
                $j++;
            }

            $to["trackers"] = $trackers;
        }
        $this->set(array(
            "torrentselectionnee" => $to,
            "host" => HOST,
            "hashtorrent" => $hashtorrentselectionne,
            "seedbox" => \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login)
        ));
    }

    function download($hashtorrentselectionne, $nofile, $keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi,
            new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "f.frozen_path", array($hashtorrentselectionne . ":f" . $nofile)));
        if ($req->success()) {
            $filename = $req->val[0];
            if ($filename == '') {
                $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi, array(
                    new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.open", $hashtorrentselectionne),
                    new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "f.frozen_path", array($hashtorrentselectionne . ":f" . $nofile)),
                    new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.close", $hashtorrentselectionne)));
                if ($req->success())
                    $filename = $req->val[1];
            }
            \model\simple\Download::sendFile($filename);
        }
        throw new \Exception("FILE NOT FOUND");
    }

    function setPrioriteFile($hashtorrentselectionne, $prio, $keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi);
        foreach ($_REQUEST["nofiles"] as $v)
            $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "f.priority.set", array($hashtorrentselectionne . ":f" . intval($v), intval($prio))));
        $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.update_priorities", $hashtorrentselectionne));
        if ($req->success())
            $result = $req->val;
    }

    function init($keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        $theSettings = \model\xmlrpc\rTorrentSettings::get(\config\Conf::$userscgi, true);
        $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi, array(
            $theSettings->getOnFinishedCommand(array("seedingtime",
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.set_custom') . '=seedingtime,"$' . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'execute_capture') . '={date,+%s}"')),
            $theSettings->getOnInsertCommand(array("addtime",
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.set_custom') . '=addtime,"$' . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'execute_capture') . '={date,+%s}"')),

            $theSettings->getOnHashdoneCommand(array("seedingtimecheck",
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'branch=') . '$' . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'not=') . '$' . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.get_complete=') . ',,' .
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.get_custom') . '=seedingtime,,"' . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.set_custom') . '=seedingtime,$' . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.get_custom') . '=addtime' . '"')),

            \model\xmlrpc\rTorrentSettings::get(\config\Conf::$userscgi)->getOnEraseCommand(array('erasedata',
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'branch=') . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.get_custom1') . '=,"' . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'execute') . '={rm,-r,$' . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.get_base_path') . '=}"')),
            $theSettings->getOnFinishedCommand(array('addbibliotheque',
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'execute') . '={' . 'php,' . ROOT . DS . 'script/addbibliotheque.php,' . \config\Conf::$userscgi . ',$' . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.get_hash') . '=,$' . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.get_base_path') . '=,$' .
                \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.get_base_filename') . '=,$' . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.is_multi_file') . '=,$' . \model\xmlrpc\rTorrentSettings::getCmd(\config\Conf::$userscgi, 'd.get_custom') . "=clefunique" . '}'
            ))
        ));
        if ($req->run()) {
            echo "ok";
        } else {
            echo $req->val;
        }
    }
} 