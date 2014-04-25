<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 26/10/13
 * Time: 07:33
 * To change this template use File | Settings | File Templates.
 */

namespace model\xmlrpc;


class rTorrent extends \core\Model {
    const RTORRENT_PACKET_LIMIT = 1572864;
    static public function sendTorrent($fname, $isStart)
    {
        $hash = false;
        $torrent = is_object($fname) ? $fname : new \model\simple\Torrent($fname);
        if(!$torrent->errors())
        {
            $raw_value = base64_encode($torrent->__toString());
            $filename = is_object($fname) ? $torrent->getFileName() : $fname;
            if((strlen($raw_value)<self::RTORRENT_PACKET_LIMIT) || is_null($filename) )
            {
                $cmd = new rXMLRPCCommand(\config\Conf::$portscgi, $isStart ? 'load_raw_start' : 'load_raw' );
                $cmd->addParameter($raw_value,"base64");
                if(!is_null($filename) && !true)
                    @unlink($filename);
            }
            else
            {
                $cmd = new rXMLRPCCommand(\config\Conf::$portscgi, $isStart ? 'load_start' : 'load' );
                $cmd->addParameter($filename);
            }
            if(!is_null($filename) && (rTorrentSettings::get(\config\Conf::$portscgi)->iVersion>=0x805))
                $cmd->addParameter(rTorrentSettings::getCmd(\config\Conf::$portscgi,"d.set_custom")."=x-filename,".rawurlencode(basename($filename)));
            $req = new rXMLRPCRequest(\config\Conf::$portscgi);
            $req->addCommand( $cmd );
            if($req->run() && !$req->fault)
                $hash = $req->val;
        }
        return($hash);
    }
}