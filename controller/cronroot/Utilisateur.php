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
        \model\simple\Console::println("Adj " . $login . " " . $scgi . (!is_null($taille) ? " " . $taille . "Go" : ""));
    }
} 