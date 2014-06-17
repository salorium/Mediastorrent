<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 08/11/13
 * Time: 11:28
 * To change this template use File | Settings | File Templates.
 */

namespace model\mysql;


class Savpass extends \core\ModelMysql
{
    public $login;
    public $password;

    function __construct()
    {
    }

    public function insert()
    {
        if (is_null($this->login))
            return false;
        $query = "insert into savpass (login,password) values(";
        $query .= \core\Mysqli::real_escape_string($this->login) . ",";
        $query .= \core\Mysqli::real_escape_string($this->password) . ")";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;

    }

    public function update()
    {
        if (!is_null($this->login)) {
            $query = "update savpass set ";
            $query .= "password=" . \core\Mysqli::real_escape_string($this->password) . " ";
            $query .= " where login=" . \core\Mysqli::real_escape_string($this->login);
            \core\Mysqli::query($query);
            $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
            \core\Mysqli::close();
            return $res;
        }
        return false;
    }

    public static function add($login)
    {
        $u = new Savpass();
        $u->login = $login;
        return $u->insert();
    }

    public function savPassword($mdp)
    {
        $this->password = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5(\config\Conf::$clefdecryptage), $mdp, MCRYPT_MODE_CBC, md5(md5(\config\Conf::$clefdecryptage))));
        return $this->update();
    }

    public static function getUserPassword($login)
    {
        $query = "select * from savpass ";
        $query .= " where login=" . \core\Mysqli::real_escape_string($login) . " and password is not null";
        \core\Mysqli::query($query);
        $u = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        if ($u) {
            return rtrim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5(\config\Conf::$clefdecryptage), base64_decode($u->password), MCRYPT_MODE_CBC, md5(md5(\config\Conf::$clefdecryptage))), "\0");
        }
        return null;

    }

    public static function deleted($login, $mdp)
    {
        $query = "update utilisateur set motdepasse=" . \core\Mysqli::real_escape_string(sha1($mdp));

        $query .= " where login=" . \core\Mysqli::real_escape_string($login);
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);

        \core\Mysqli::close();
        return $res;

    }

    public static function authentifierUtilisateurParKeyConnexion($login, $key)
    {
        $query = "select * from utilisateur ";
        $query .= " where login=" . \core\Mysqli::real_escape_string($login) . " and keyconnexion=" . \core\Mysqli::real_escape_string($key);
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