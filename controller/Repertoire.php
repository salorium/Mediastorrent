<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 08/07/14
 * Time: 00:23
 */

namespace controller;


class Repertoire extends \core\Controller
{
    function liste($login, $keyconnexion)
    {
        \model\simple\Utilisateur::authentificationDistante($login, $keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if (!\config\Conf::$user["role"] >= \config\Conf::$rolenumero["Torrent"]) throw new \Exception("Non authorisé");
        $vv = \model\simple\Repertoire::getFindAll();
        $this->set("rep", $vv);
    }
} 