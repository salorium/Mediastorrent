<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 17/05/14
 * Time: 09:36
 */

namespace controller\cronroot;


use model\simple\Mail;

class Utilisateur extends \core\Controller
{
    function addRtorrent($login, $scgi, $taille = null)
    {
        $res = null;
        $err = false;
        \model\simple\Console::println("Adj rtorrent " . $login . " " . $scgi . (!is_null($taille) ? " " . $taille . "Go" : ""));
        try {
            \model\bash\Utilisateur::addRtorrent($login, $scgi, $taille);
            $res["rtorrentsadj"] = \model\mysql\Rtorrents::addRtorrentUtilisateurScgi($login, \config\Conf::$nomrtorrent, $scgi);
        } catch (\Exception $e) {
            \model\simple\Console::println($e->getMessage());
            $err = true;
        }
        $res["system"] = \model\simple\Console::$query;
        if ($err) {
            $us = \model\mysql\Utilisateur::getAllUtilisateurSysop();
            foreach ($us as $u) {
                Mail::infosSysopErreurAdjRtorrent($u->mail, $res);
            }
        }
        return $res;
    }

    function delRtorrent($login)
    {
        $res = null;
        $err = false;
        try {
            \model\bash\Utilisateur::delRtorrent($login);
        } catch (\Exception $e) {
            \model\simple\Console::println($e->getMessage());
            $err = true;
        }
        $res["system"] = \model\simple\Console::$query;
        if ($err) {
            $us = \model\mysql\Utilisateur::getAllUtilisateurSysop();
            foreach ($us as $u) {
                Mail::infosSysopErreurAdjRtorrent($u->mail, $res);
            }
        }
        return $res;
    }

    function rebootRtorrent($login)
    {
        $res = null;
        \model\simple\Console::println("Reboot rtorrent " . $login);
        \model\bash\Utilisateur::rebootRtorrent($login);
        $res["system"] = \model\simple\Console::$query;
        return $res;
    }

    function tester()
    {
        return true;
    }
} 