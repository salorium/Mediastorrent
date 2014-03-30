<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 08/11/13
 * Time: 11:28
 * To change this template use File | Settings | File Templates.
 */

namespace model\mysql;


class Utilisateur extends \core\ModelMysql{
    public $login;
    public $motdepasse;
    public $mail;
    public $role;
    public $keyconnexion;

    function __construct()
    {
    }
    public  function update(){
        if(!is_null($this->login)){
            $query = "update utilisateur set ";
            $query.="motdepasse=";
            if(is_null($this->motdepasse)){
                $query.="NULL,";
            }else{
                $query.= "'". \core\Mysqli::real_escape_string($this->motdepasse)."', ";
            }
            $query.="mail=";
            if(is_null($this->mail)){
                $query.="NULL,";
            }else{
                $query.= "'". \core\Mysqli::real_escape_string($this->mail)."', ";
            }
            $query.="role=";
            if(is_null($this->role)){
                $query.="NULL,";
            }else{
                $query.=   "'". \core\Mysqli::real_escape_string($this->role)."', ";
            }
            $query.="keyconnexion=";
            if(is_null($this->keyconnexion)){
                $query.="NULL";
            }else{
                $query.= "'". \core\Mysqli::real_escape_string($this->keyconnexion)."'";
            }
            $query.= " where login='". \core\Mysqli::real_escape_string($this->login)."'";
            \core\Mysqli::query($query);
            $res =  (\core\Mysqli::nombreDeLigneAffecte() == 1 );
            \core\Mysqli::close();
            return $res;
        }
        return false;
    }
    public static function authentifierUtilisateurParMotDePasse($login,$mdp){
        $query = "select * from utilisateur ";
        $query.= " where login='". \core\Mysqli::real_escape_string($login)."' and motdepasse='".\sha1($mdp)."'";
        \core\Mysqli::query($query);
        $u =  \core\Mysqli::getObjectAndClose(false,__CLASS__);
        if ($u){
            $u->keyconnexion = \sha1(\uniqid().$u->login.$u->motdepasse);
            if ($u->update()){
                return $u;
            }

        }
        return false;

    }

    public static function getUtilisateurParMail( $mail){
        $query = "select * from utilisateur ";
        $query.= " where mail='". \core\Mysqli::real_escape_string($mail)."'";
        \core\Mysqli::query($query);
        $u =  \core\Mysqli::getObjectAndClose(false,__CLASS__);
        return $u;

    }

    public static function modifierMotDePasse( $login,$mdp){
        $query = "update utilisateur set motdepasse='".\core\Mysqli::real_escape_string(sha1($mdp))."'";

        $query.= " where login='". \core\Mysqli::real_escape_string($login)."'";
        \core\Mysqli::query($query);
        $res =  (\core\Mysqli::nombreDeLigneAffecte() == 1 );

        \core\Mysqli::close();
        return $res;

    }

    public static function authentifierUtilisateurParKeyConnexion($login,$key){
        $query = "select * from utilisateur ";
        $query.= " where login='". \core\Mysqli::real_escape_string($login)."' and keyconnexion='". \core\Mysqli::real_escape_string($key)."'";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false,__CLASS__);
    }

}