<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 27/03/14
 * Time: 13:43
 */

namespace model\simple;


class Ssh extends \core\Model {
    static function execute ($user,$mdp,$cmd){
        $connection = \ssh2_connect('localhost', 22);
        \ssh2_auth_password($connection, $user, $mdp);
        $table=array();
        $stream = \ssh2_exec($connection, $cmd);
        sleep(1);
        stream_set_blocking($stream,true);
        $stderr_stream = \ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        $res = "";
        while($line = fgets($stream)) {/*echo $line; flush();*/$res.= $line."\n";}
        $table["sortie"]= $res;
        fclose($stream);
        $res= "";
        while($line = fgets($stderr_stream)) { $res.= $line."\n"; }
        $table["error"] = $res;

        fclose($stderr_stream);

        return $table;
    }


} 