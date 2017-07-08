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
    public $options;

    function __construct()
    {
    }

    public function insert()
    {
        if (is_null($this->login))
            return false;
        $query = "insert into utilisateur (login,motdepasse,mail,role,keyconnexion,options) values(";
        $query .= \core\Mysqli::real_escape_string_html($this->login) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->motdepasse) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->mail) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->role) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->keyconnexion) . ",";
        $query .= \core\Mysqli::real_escape_json($this->options) . ")";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;

    }

    public function update()
    {
        if (!is_null($this->login)) {
            $query = "update utilisateur set ";
            $query .= "motdepasse=" . \core\Mysqli::real_escape_string_html($this->motdepasse) . ", ";
            $query .= "mail=" . \core\Mysqli::real_escape_string_html($this->mail) . ", ";
            $query .= "role=" . \core\Mysqli::real_escape_string_html($this->role) . ", ";
            $query .= "options=" . \core\Mysqli::real_escape_json($this->options) . ", ";
            $query .= "keyconnexion=" . \core\Mysqli::real_escape_string_html($this->keyconnexion);
            $query .= " where login=" . \core\Mysqli::real_escape_string_html($this->login);
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

    public static function insertUtilisateur($login, $mdp, $role, $mail, $options = null)
    {
        $u = new Utilisateur();
        $u->login = $login;
        $u->motdepasse = sha1($mdp);
        $u->mail = $mail;
        $u->role = $role;
        $u->options = $options;
        return $u->insert();
    }

    public static function authentifierUtilisateurParMotDePasse($login, $mdp)
    {
        $query = "select * from utilisateur ";
        $query .= " where login=" . \core\Mysqli::real_escape_string_html($login) . " and motdepasse=" . \core\Mysqli::real_escape_string_html(sha1($mdp));
        \core\Mysqli::query($query);
        $u = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        if ($u) {
            $u->options = json_decode($u->options);
            do {
                $u->keyconnexion = \model\simple\ChaineCaractere::random(40);
            } while (!$u->update());
            return $u;
        }
        return false;

    }

    public static function getUtilisateurParMail($mail)
    {
        $query = "select * from utilisateur ";
        $query .= " where mail=" . \core\Mysqli::real_escape_string_html($mail);
        \core\Mysqli::query($query);
        $u = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        return $u;

    }

    public static function modifierMotDePasse($login, $mdp)
    {
        $query = "update utilisateur set motdepasse=" . \core\Mysqli::real_escape_string_html(sha1($mdp));

        $query .= " where login=" . \core\Mysqli::real_escape_string_html($login);
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);

        \core\Mysqli::close();
        return $res;

    }

    public static function authentifierUtilisateurParKeyConnexion($key)
    {
        $query = "select * from utilisateur ";
        $query .= " where keyconnexion=" . \core\Mysqli::real_escape_string_html($key);
        \core\Mysqli::query($query);
        $u = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        if ($u) {
            $u->options = json_decode($u->options);
        }
        return $u;
    }

    public static function getAllUtilisateur()
    {
        $query = "select * from utilisateur";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true, __CLASS__);
    }

    public static function getAllUtilisateurSysop()
    {
        $query = "select * from utilisateur where role='Sysop'";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true, __CLASS__);
    }

    public static function getUtilisteur($login)
    {
        $query = "select * from utilisateur";
        $query .= " where login=" . \core\Mysqli::real_escape_string_html($login);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);
    }

    public static function supprimeUtilisateur($login)
    {
        $query = "delete from utilisateur ";
        $query .= " where login=" . \core\Mysqli::real_escape_string_html($login);
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;
    }
}