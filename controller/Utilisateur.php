<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 25/10/13
 * Time: 11:58
 * To change this template use File | Settings | File Templates.
 */

namespace controller;


use model\mysql\Rtorrent;

class Utilisateur extends \core\Controller
{
    function view($nom)
    {
        $this->set("nom", $nom);
        $test = new \model\mysql\Utilisateur();
        $test->find(array(
            "conditions" => array(
                array(array("colums" => "login", "table" => new \model\mysql\Utilisateur()), "=", array("colums" => "login", "table" => new \model\mysql\Rtorrents()))
            )

        ));
        \core\Mysqli::query("select * from tr");
    }

    function index()
    {
        //\debug($_SERVER);
        //throw new \Exception("dds");
    }

    function connexion()
    {
        if (!(isset($_POST["login"]) && isset($_POST["motdepasse"])))
            $this->render("index");
        $u = \model\mysql\Utilisateur::authentifierUtilisateurParMotDePasse($_POST["login"], $_POST["motdepasse"]);
        if (is_object($u)) {
            // header("HTTP/1.1 307 Temporary Redirect");
            if (!\core\Memcached::value($u->keyconnexion, "user", $u, 60 * 5))
                trigger_error("Impossible de mettre des données dans memcached");
            //setcookie("login", $u->login, strtotime('+1 days'), "/");
            setcookie("keyconnexion", $u->keyconnexion, strtotime('+1 days'), "/");
            header("Location: " . BASE_URL);
            exit();
        } else {
            // header("HTTP/1.1 307 Temporary Redirect");
            $this->set(array(
                "login" => $_POST["login"],
                "erreur" => true

            ));
            $this->render("index");
        }
    }

    function getKeyconnexion()
    {
        if (isset($_COOKIE["keyconnexion"])) {
            $u = \core\Memcached::value($_COOKIE["keyconnexion"], "user");
            if (is_null($u)) {
                $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($_COOKIE["keyconnexion"]);
                if ($u)
                    \core\Memcached::value($_COOKIE["keyconnexion"], "user", $u, 60 * 5);
            } else {
                $u = $u->keyconnexion === $_COOKIE["keyconnexion"] ? $u : false;
            }
            $this->set("seedbox", Rtorrent::getPortscgiDeUtilisateur(\config\Conf::$user["user"]->login));
        }

        if ($u && !is_null($u)) {
            \core\Memcached::value($_COOKIE["keyconnexion"], "user", $u, 60 * 5);
            \config\Conf::$user["user"] = $u;
            $this->set("key", $u->keyconnexion);

        } else {
            $u = \model\mysql\Utilisateur::authentifierUtilisateurParMotDePasse($_REQUEST["login"], $_REQUEST["motdepasse"]);
            if (is_object($u)) {
                $this->set("key", $u->keyconnexion);
                if (!\core\Memcached::value($u->keyconnexion, "user", $u, 60 * 5))
                    trigger_error("Impossible de mettre des données dans memcached");
                //setcookie("login", $u->login, strtotime('+1 days'), "/");
                setcookie("keyconnexion", $u->keyconnexion, strtotime('+1 days'), "/");
                \config\Conf::$user["user"] = $u;
            }
        }
        if (is_null(\config\Conf::$user["user"])) {
            $this->set("erreur", 1);

        } else {
            $this->set("seedbox", \model\mysql\Rtorrent::getRtorrentsDeUtilisateur(\config\Conf::$user["user"]->login));
        }

    }

    function connexionApi()
    {
        //if (!(isset($_POST["login"]) && isset($_POST["motdepasse"])))
        $u = \model\mysql\Utilisateur::authentifierUtilisateurParMotDePasse($_POST["login"], $_POST["motdepasse"]);
        if (is_object($u))
            $this->set("key", $u->keyconnexion);
        $this->set("cookie", $_COOKIE);

    }

    function mdpoublier()
    {
        if (isset ($_POST["mail"])) {
            $u = \model\mysql\Utilisateur::getUtilisateurParMail($_POST["mail"]);
            if ($u) {
                do {
                    $mdp = \model\simple\String::random(8);
                } while (preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/', $mdp) != 1);

                $args["login"] = $u->login;
                $args["mdp"] = $mdp;
                $t = \model\mysql\Ticket::savTicket("controller\\horsligne\\Utilisateur", "modifierMdp", $args);
                $f = false;
                if (!is_bool($t))
                    $f = \model\simple\Mail::activationMotDePasse($u->mail, $u->login, $mdp, $t);
                $this->set(array(
                    "succereinitialmdp" => $f,
                ));
                $this->render("index");
            } else {
                $this->set(array(
                    "erreur" => true
                ));
            }
        }
    }

}