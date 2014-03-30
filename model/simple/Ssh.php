<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 27/03/14
 * Time: 13:43
 */

namespace model\simple;


class Ssh extends \core\Model {
    static function execute ($user,$cmd){
        $connection = \ssh2_connect('localhost', 22);
        \ssh2_auth_password($connection, 'root', 'azerty');

        $stream = \ssh2_exec($connection, "su -l ".$user." -c '".$cmd."'");
        sleep(1);
        $stderr_stream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        $table=array();
        $res = "";
        while($line = fgets($stderr_stream)) { flush(); $res.= $line."\n"; }
        $table["error"] = $res;
        $res= "";

        while($line = fgets($stream)) { flush(); $res.= $line."\n";}
        $table["sortie"]= $res;
        fclose($stream);
        return $table;
    }

    static function supprime($user,$directory){
        $cmd = "rm -R ".$directory;
        return self::execute($user,$cmd);

    }
} 