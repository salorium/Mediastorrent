<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 25/04/14
 * Time: 15:50
 */

namespace model\simple;


class Utilisateur extends \core\Model
{
    static function authentificationPourRtorrent($login, $keyconnexion)
    {
        if (!is_null($login) && !is_null($keyconnexion)) {
            $u = \core\Memcached::value($login, "user");
            if (is_null($u)) {
                $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($login, $keyconnexion);
                if ($u)
                    \core\Memcached::value($u->login, "user", $u, 60 * 1);
            } else {
                /*if (!is_object($u)) {
                    var_dump($u);
                    var_dump(\core\Memcached::$request);
                    die();
                }*/

                $u = $u->keyconnexion === $keyconnexion ? $u : false;
                if (is_bool($u)) {
                    $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($login, $keyconnexion);
                    if ($u)
                        \core\Memcached::value($u->login, "user", $u, 60 * 1);
                }
                $u = $u->keyconnexion === $keyconnexion ? $u : false;
            }
            \config\Conf::$user["user"] = $u;
            if (!is_bool($u)) {
                $portscgi = \model\mysql\Rtorrent::getPortscgiDeUtilisateur($login);
                if (!$portscgi) {
                    throw new \Exception("Aucun ports scgi sur " . HOST);
                }
                \config\Conf::$portscgi = $portscgi[0]->portscgi;
            }
        }
    }
} 