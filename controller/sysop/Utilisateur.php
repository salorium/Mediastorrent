<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 06/05/14
 * Time: 18:19
 */

namespace controller\sysop;


use model\simple\Mail;

class Utilisateur extends \core\Controller
{
    public $layout = "connecter";

    function liste()
    {
        if (isset($_REQUEST['action'])) {
            switch ($_REQUEST['action']) {
                case 'addrtorrent':
                    $a = \model\mysql\Cronroot::sav($_REQUEST["nomrtorrent"], "controller\\cronroot\\Utilisateur", "addRtorrent", array("login" => $_REQUEST["login"], "taille" => (isset($_REQUEST["taille"]) ? $_REQUEST["taille"] : null)));
                    break;
                case 'deluser':
                    $rts = \model\mysql\Rtorrents::getAllRtorrentUtilisateur($_REQUEST["login"]);
                    if (is_array($rts)) {
                        foreach ($rts as $v) {
                            $a = \model\mysql\Cronroot::sav($v->nomrtorrent, "controller\\cronroot\\Utilisateur", "delRtorrent", array("login" => $_REQUEST["login"]));

                        }
                    }
                    $a = \model\mysql\Utilisateur::supprimeUtilisateur($_REQUEST["login"]);
                    break;

            }
        }
        $users = \model\mysql\Utilisateur::getAllUtilisateur();
        $user = $users[0];
        if (isset($_REQUEST["login"])) {
            $u = \model\mysql\Utilisateur::getUtilisteur($_REQUEST["login"]);
            if (!is_bool($u)) {
                $user = $u;
            }
        }
        if (\model\simple\Utilisateur::checkRoleOk($user->role, "Torrent")) {
            $rtorrents = \model\mysql\Rtorrents::getRtorrentsDispoPourUtilisateur($user->login);
        } else {
            $rtorrents = array();
        }
        //debug($rtorrents["VPS1"]);
        $this->set(array(
            "users" => $users,
            "role" => array_slice(\config\Conf::$numerorole, 2),
            "rtorrents" => $rtorrents,
            "user" => $user
        ));
    }

    function create()
    {
        if (!(isset($_REQUEST["login"]) && isset($_REQUEST["mail"]) && isset($_REQUEST["role"])))
            throw new \Exception("Manque les variables post");
        $mdp = \model\simple\Utilisateur::getRandomMdp();
        $options = null;
        if (isset($_REQUEST["vlc"]))
            $options["vlc"] = true;
        if (!\model\mysql\Utilisateur::insertUtilisateur($_REQUEST["login"], $mdp, $_REQUEST["role"], $_REQUEST["mail"], $options))
            throw new \Exception("Impossible d'enregistrer cet utilisateur");

        Mail::creationCompte($_REQUEST["mail"], $_REQUEST["login"], $mdp);

    }
} 