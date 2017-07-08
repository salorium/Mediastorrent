<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 26/10/13
 * Time: 07:33
 * To change this template use File | Settings | File Templates.
 */

namespace model\rtorrent;


use core\Debug;

class rTorrent extends \core\Model
{
    const RTORRENT_PACKET_LIMIT = 1572864;

    public static function listeTorrent($viewName = "add")
    {
        $cmds = array(
            "d.hash=" /*1*/,
            "d.is_active=" /*2*/,
            "d.is_open=" /*3*/,
            "d.is_hash_checking=" /*4*/,
            "d.hashing=" /*5*/,

            "d.state=" /*6*/,
            "d.name=" /*7*/,
            "d.bytes_done=" /*8*/,
            "d.size_bytes=" /*9*/,
            "d.timestamp.started=" /*10*/,

            "d.timestamp.finished=" /*11*/,
            "d.ratio="/*12*/,
            'cat="$t.multicall=d.hash=,t.scrape_complete=,cat={#}"' /*13*/,
            "d.peers_complete=" /*14*/,
            'cat="$t.multicall=d.hash=,t.scrape_incomplete=,cat={#}"' /*15*/,

            "d.peers_accounted=" /*16*/,
            "d.up.rate=" /*17*/,
            "d.down.rate=" /*18*/,
            "d.down.total=" /*19*/,
            "d.up.total=" /*20*/,

            "d.message="/*21*/,

        );
        $cmd = new rXMLRPCCommand("d.multicall2", array("", $viewName));
        foreach ($cmds as $c) {
            $cmd->addParameters($c);
        }
        $req = new rXMLRPCRequest($cmd);
        $tmp1 = null;
        if ($req->success(false)) {
            Debug::startTimer("re");
            $regex = "";
            for ($i = 0; $i < count($cmds); $i++) {
                $regex .= "<value>(?:<string>|<i.>)(.*)(?:<\/string>|<\/i.>)<\/value>\s*";
            }
            $i = preg_match_all("/" . $regex . "/", $req->val, $tmp1);
            Debug::endTimer("re");
            $status = array('started' => 1, 'paused' => 2, 'checking' => 4, 'hashing' => 8, 'error' => 16);
            $torrents = [];
            Debug::startTimer("compileTorrent");
            for ($j = 0; $j < $i; $j++) {
                $hash = (string)$tmp1[1][$j];
                $is_active = (boolean)(int)$tmp1[2][$j];
                $is_open = (boolean)(int)$tmp1[3][$j];
                $is_hash_checking = (boolean)(int)$tmp1[4][$j];
                $is_hashing = (boolean)(int)$tmp1[5][$j];
                $state = 0;
                $is_state = (boolean)(int)$tmp1[6][$j];
                if ($is_open) {
                    $state |= $status["started"];
                    if ((!$is_state) || (!$is_active))
                        $state |= $status["paused"];
                }
                if ($is_hashing)
                    $state |= $status["hashing"];
                if ($is_hash_checking != 0)
                    $state |= $status["checking"];
                $msg = (string)$tmp1[21][$j];
                if ($msg != "" && $msg != "Tracker: [Tried all trackers.]")
                    $state |= $status["error"];
                $name = (string)$tmp1[7][$j];
                $bytesDone = (int)$tmp1[8][$j];
                $bytesTotal = (int)$tmp1[9][$j];
                $timeAdd = (int)$tmp1[10][$j];
                $timeSeed = (int)$tmp1[11][$j];
                $ratio = (int)$tmp1[12][$j];
                $seedsTotal = 0;
                foreach (explode("#", (string)$tmp1[13][$j]) as $k => $v) {
                    $seedsTotal += $v;
                }
                $seeds = (int)$tmp1[14][$j];
                $peersTotal = 0;
                foreach (explode("#", (string)$tmp1[15][$j]) as $k => $v) {
                    $peersTotal += $v;
                }
                $peers = (int)$tmp1[16][$j];
                $vitessUpload = (int)$tmp1[17][$j];
                $vitessDownload = (int)$tmp1[18][$j];
                $bytesDownload = (int)$tmp1[19][$j];
                $bytesUpload = (int)$tmp1[20][$j];
                $torrents[] = [$hash, $name, $state, $bytesDone, $bytesTotal, $timeAdd, $timeSeed, $ratio,
                    $seedsTotal, $seeds, $peersTotal, $peers, $vitessUpload, $vitessDownload];
            }
            Debug::endTimer("compileTorrent");
//            die();


//            return $req->val;
        }

        return $torrents;
        //die();
    }

    static public function sendTorrent($fname, $isStart, $directory = null)
    {
        $hash = false;
        $torrent = is_object($fname) ? $fname : new \model\simple\Torrent($fname);
        if (!$torrent->errors()) {
            $raw_value = base64_encode($torrent->__toString());
            $filename = is_object($fname) ? $torrent->getFileName() : $fname;
            if ((strlen($raw_value) < self::RTORRENT_PACKET_LIMIT) || is_null($filename)) {
                $cmd = new rXMLRPCCommand(\config\Conf::$userscgi, $isStart ? 'load.raw_start' : 'load.raw');
                $cmd->addParameter("");
                $cmd->addParameter($raw_value, "base64");
                if (!is_null($filename) && !true)
                    @unlink($filename);
            } else {
                $cmd = new rXMLRPCCommand(\config\Conf::$userscgi, $isStart ? 'load.start' : 'load.normal');
                $cmd->addParameter("");
                $cmd->addParameter($filename);
            }
            if (!is_null($filename) && (rTorrentSettings::get(\config\Conf::$userscgi)->iVersion >= 0x805))
                $cmd->addParameter(rTorrentSettings::getCmd(\config\Conf::$userscgi, "d.custom.set") . "=x-filename," . rawurlencode(basename($filename)));
            $req = new rXMLRPCRequest(\config\Conf::$userscgi);
            $req->addCommand($cmd);
            if (!is_null($directory))
                $cmd->addParameter(rTorrentSettings::getCmd(\config\Conf::$userscgi, "d.directory.set=") . "\"" . $directory . "\"");
            if ($req->run() && !$req->fault)
                $hash = $req->val;
        }
        return ($hash);
    }

    static public function fastResume($torrent, $base, $add_path = true)
    {
        $files = array();
        $info = $torrent->info;
        $psize = intval($info['piece length']);
        $base = trim($base);
        if ($base == '') {
            $req = new rXMLRPCRequest(new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, 'get_directory'));
            if ($req->success())
                $base = $req->val[0];
        }
        if ($psize && \model\xmlrpc\rTorrentSettings::get(\config\Conf::$userscgi)->correctDirectory($base)) {
            $base = addslash($base);
            $tsize = 0.0;
            if (isset($info['files'])) {
                foreach ($info['files'] as $key => $file) {
                    $tsize += floatval($file['length']);
                    $files[] = ($add_path ? $info['name'] . "/" . implode('/', $file['path']) : implode('/', $file['path']));
                }
            } else {
                $tsize = floatval($info['length']);
                $files[] = $info['name'];
            }
            $chunks = intval(($tsize + $psize - 1) / $psize);
            $torrent->{'libtorrent_resume'}['bitfield'] = intval($chunks);
            if (!isset($torrent->{'libtorrent_resume'}['files']))
                $torrent->{'libtorrent_resume'}['files'] = array();
            foreach ($files as $key => $file) {
                $ss = LFS::stat($base . $file);
                if ($ss === false)
                    return (false);
                if (count($torrent->{'libtorrent_resume'}['files']) < $key)
                    $torrent->{'libtorrent_resume'}['files'][$key]['mtime'] = $ss["mtime"];
                else
                    $torrent->{'libtorrent_resume'}['files'][$key] = array("priority" => 2, "mtime" => $ss["mtime"]);
            }
            return ($torrent);
        }
        return (false);
    }
}