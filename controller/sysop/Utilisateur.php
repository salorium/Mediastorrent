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
        $user = $users[0];
        if (isset($_REQUEST["login"])) {
            $u = \model\mysql\Utilisateur::getUtilisteur($_REQUEST["login"]);
            if (!is_bool($u)) {
                $user = $u;
            }
        }
        $rtorrents = \model\mysql\Rtorrents::getRtorrentsDispoPourUtilisateur($user->login);
        //debug($rtorrents["VPS1"]);
        $this->set(array(
            "users" => $users,
            "rtorrents" => $rtorrents,
            "user" => $user
        ));
    }
} 