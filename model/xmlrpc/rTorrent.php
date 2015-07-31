<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 26/10/13
 * Time: 07:33
 * To change this template use File | Settings | File Templates.
 */

namespace model\xmlrpc;


class rTorrent extends \core\Model
{
    const RTORRENT_PACKET_LIMIT = 1572864;

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