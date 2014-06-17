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
    static function authentificationDistante($login, $keyconnexion)
    {
        if (!is_null($login) && !is_null($keyconnexion)) {
            $u = \core\Memcached::value($login, "user");
            if (is_null($u)) {
                $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($login, $keyconnexion);
                if ($u)
                    \core\Memcached::value($u->login, "user", $u, 60 * 2);
            } else {
                $u = $u->keyconnexion === $keyconnexion ? $u : false;
                if (is_bool($u)) {
                    $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($login, $keyconnexion);
                    if ($u)
                        \core\Memcached::value($u->login, "user", $u, 60 * 2);
                } else {
                    \core\Memcached::value($u->login, "user", $u, 60 * 2);
                }
            }
            \config\Conf::$user["user"] = $u;
        }
    }

    static function authentificationPourRtorrent($login, $keyconnexion)
    {
        if (!is_null($login) && !is_null($keyconnexion)) {
            $u = \core\Memcached::value($login, "user");
            if (is_null($u)) {
                $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($login, $keyconnexion);
                if ($u)
                    \core\Memcached::value($u->login, "user", $u, 60 * 2);
            } else {
                $u = $u->keyconnexion === $keyconnexion ? $u : false;
                if (is_bool($u)) {
                    $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($login, $keyconnexion);
                    if ($u)
                        \core\Memcached::value($u->login, "user", $u, 60 * 2);
                } else {
                    \core\Memcached::value($u->login, "user", $u, 60 * 2);
                }
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