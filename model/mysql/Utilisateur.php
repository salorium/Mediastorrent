<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 08/11/13
 * Time: 11:28
 * To change this template use File | Settings | File Templates.
 */

namespace model\mysql;


class Utilisateur extends \core\ModelMysql
{
    public $login;
    public $motdepasse;
    public $mail;
    public $role;
    public $keyconnexion;

    function __construct()
    {
    }

    public function insert()
    {
        if (is_null($this->login))
            return false;
        $query = "insert into utilisateur (login,motdepasse,mail,role,keyconnexion) values(";
        $query .= \core\Mysqli::real_escape_string($this->login) . ",";
        $query .= \core\Mysqli::real_escape_string($this->motdepasse) . ",";
        $query .= \core\Mysqli::real_escape_string($this->mail) . ",";
        $query .= \core\Mysqli::real_escape_string($this->role) . ",";
        $query .= \core\Mysqli::real_escape_string($this->keyconnexion) . ")";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;

    }

    public function update()
    {
        if (!is_null($this->login)) {
            $query = "update utilisateur set ";
            $query .= "motdepasse=" . \core\Mysqli::real_escape_string($this->motdepasse) . ", ";
            $query .= "mail=" . \core\Mysqli::real_escape_string($this->mail) . ", ";
            $query .= "role=" . \core\Mysqli::real_escape_string($this->role) . ", ";
            $query .= "keyconnexion=" . \core\Mysqli::real_escape_string($this->keyconnexion);
            $query .= " where login=" . \core\Mysqli::real_escape_string($this->login);
            \core\Mysqli::query($query);
            //\model\simple\Console::println("Requête sql :[" . $query . "]");
            $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
            \core\Mysqli::close();
            return $res;
        }
        return false;
    }

    public static function insertUtilisateurSysop($login, $mdp, $mail)
    {
        $u = new Utilisateur();
        $u->login = $login;
        $u->motdepasse = sha1($mdp);
        $u->mail = $mail;
        $u->role = "Sysop";
        return $u->insert();
    }

    public static function authentifierUtilisateurParMotDePasse($login, $mdp)
    {
        $query = "select * from utilisateur ";
        $query .= " where login=" . \core\Mysqli::real_escape_string($login) . " and motdepasse=" . \core\Mysqli::real_escape_string(sha1($mdp));
        \core\Mysqli::query($query);
        $u = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        if ($u) {
            do {
                $u->keyconnexion = \model\simple\String::random(40);
            } while (!$u->update());
            return $u;
        }
        return false;

    }

    public static function getUtilisateurParMail($mail)
    {
        $query = "select * from utilisateur ";
        $query .= " where mail=" . \core\Mysqli::real_escape_string($mail);
        \core\Mysqli::query($query);
        $u = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        return $u;

    }

    public static function modifierMotDePasse($login, $mdp)
    {
        $query = "update utilisateur set motdepasse=" . \core\Mysqli::real_escape_string(sha1($mdp));

        $query .= " where login=" . \core\Mysqli::real_escape_string($login);
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);

        \core\Mysqli::close();
        return $res;

    }

    public static function authentifierUtilisateurParKeyConnexion($key)
    {
        $query = "select * from utilisateur ";
        $query .= " where keyconnexion=" . \core\Mysqli::real_escape_string($key);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);
    }

    public static function getAllUtilisateur()
    {
        $query = "select * from utilisateur";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true, __CLASS__);
    }

    public static function getUtilisteur($login)
    {
        $query = "select * from utilisateur";
        $query .= " where login=" . \core\Mysqli::real_escape_string($login);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);
    }
}