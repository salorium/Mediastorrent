<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 06/05/14
 * Time: 18:19
 */

namespace controller\sysop;


class Utilisateur extends \core\Controller
{
    public $layout = "connecter";

    function liste()
    {
        $users = \model\mysql\Utilisateur::getAllUtilisateur();
        $login = $users[0]->login;
        if (isset($_REQUEST["login"]) && \model\mysql\Utilisateur::existeUtilisteur($_REQUEST["login"]))
            $login = $_REQUEST["login"];
        $rtorrents = \model\mysql\Rtorrents::getRtorrentsDispoPourUtilisateur($login);
        //debug($rtorrents["VPS1"]);
        $this->set(array(
            "users" => $users,
            "rtorrents" => $rtorrents,
            "login" => $login
        ));
    }
} 