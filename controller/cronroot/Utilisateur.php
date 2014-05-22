<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 17/05/14
 * Time: 09:36
 */

namespace controller\cronroot;


class Utilisateur extends \core\Controller
{
    function addRtorrent($login, $scgi, $taille = null)
    {
        $res = null;
        \model\simple\Console::println("Adj rtorrent " . $login . " " . $scgi . (!is_null($taille) ? " " . $taille . "Go" : ""));
        \model\bash\Utilisateur::addRtorrent($login, $scgi, $taille);
        $res["rtorrentsadj"] = \model\mysql\Rtorrents::addRtorrentUtilisateurScgi($login, \config\Conf::$nomrtorrent, $scgi);
        $res["system"] = \model\simple\Console::$query;
        return $res;
    }
} 