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
    static function authentificationDistante($keyconnexion)
    {
        foreach (\config\Conf::$numerorole as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $kk => $vv) {
                    \config\Conf::$rolenumero [$vv] = $k;
                }
            } else {
                \config\Conf::$rolenumero [$v] = $k;
            }

        }
        if (!is_null($keyconnexion)) {
            $u = \core\Memcached::value($keyconnexion, "user");
            if (is_null($u)) {
                $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($keyconnexion);
                if ($u)
                    \core\Memcached::value($u->keyconnexion, "user", $u, 60 * 2);

            } else {
                $u = $u->keyconnexion === $keyconnexion ? $u : false;
                if (is_bool($u)) {
                    $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($keyconnexion);
                    if ($u)
                        \core\Memcached::value($u->keyconnexion, "user", $u, 60 * 2);
                } else {
                    \core\Memcached::value($u->keyconnexion, "user", $u, 60 * 2);
                }
            }
            \config\Conf::$user["user"] = $u;
        }
        $role = 1;
        $roletext = "Visiteur";
        if ($u && !is_null($u)) {
            $role = \config\Conf::$rolenumero[$u->role];
            $roletext = $u->role;
            \core\Memcached::value($u->keyconnexion, "user", $u, 60 * 5);
            setcookie("keyconnexion", $u->keyconnexion, strtotime('+1 days'), "/");
        }
        if ( is_null(\config\Conf::$user["user"] ))
        \config\Conf::$user["user"] = $u;
        \config\Conf::$user["role"] = $role;
        \config\Conf::$user["roletxt"] = $roletext;
        if ($u) {
            \core\LoaderJavascript::add("base", "controller.setUtilisateur", array(\config\Conf::$user["user"]->login, \config\Conf::$user["user"]->keyconnexion, \config\Conf::$user["user"]->role));
        }
    }

    static function authentificationPourRtorrent($keyconnexion)
    {
        if (!is_null($keyconnexion)) {
            $u = \core\Memcached::value($keyconnexion, "user");
            if (is_null($u)) {
                $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($keyconnexion);
                if ($u)
                    \core\Memcached::value($u->keyconnexion, "user", $u, 60 * 2);
                } else {
                $u = $u->keyconnexion === $keyconnexion ? $u : false;
                if (is_bool($u)) {
                    $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($keyconnexion);
                    if ($u)
                        \core\Memcached::value($u->keyconnexion, "user", $u, 60 * 2);
                } else {
                    \core\Memcached::value($u->keyconnexion, "user", $u, 60 * 2);
                }
            }
            \config\Conf::$user["user"] = $u;
            if ($u && !is_null($u)) {
                setcookie("keyconnexion", $u->keyconnexion, strtotime('+1 days'), "/");
                $userscgi = \model\mysql\Rtorrent::getUserscgiDeUtilisateur($u->login);
                if (!$userscgi) {
                    throw new \Exception("Aucun ports scgi sur " . HOST);
                }
                \config\Conf::$userscgi = $userscgi[0]->userscgi;
            }
        }
    }

    static function checkRoleOk($roleuser, $role)
    {
        return (\config\Conf::$rolenumero[$role] <= \config\Conf::$rolenumero[$roleuser]);
    }

    static function getRandomMdp()
    {
        do {
            $mdp = \model\simple\ChaineCaractere::random(8);
        } while (preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $mdp) != 1);
        return $mdp;
    }
} 