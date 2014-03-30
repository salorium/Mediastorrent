<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 25/10/13
 * Time: 11:58
 * To change this template use File | Settings | File Templates.
 */

namespace controller;


class Utilisateur extends \core\Controller {
    function view($nom){
        $this->set("nom",$nom);
        $test = new \model\mysql\Utilisateur();
        $test->find(array(
            "conditions" => array(
                array(array("colums"=>"login","table"=>new \model\mysql\Utilisateur()),"=",array("colums"=>"login","table"=>new \model\mysql\Rtorrents()))
            )

        ));
        \core\Mysqli::query("select * from tr");
    }

    function index(){
        //\debug($_SERVER);
        //throw new \Exception("dds");
    }

    function connexion(){
        if (!(isset($_POST["login"]) && isset($_POST["motdepasse"])))
            $this->render("index");
        $u = \model\mysql\Utilisateur::authentifierUtilisateurParMotDePasse($_POST["login"],$_POST["motdepasse"]);
        if (is_object($u)){
            // header("HTTP/1.1 307 Temporary Redirect");
            if (! \core\Memcached::value($u->login,"user",$u,60*5))
                trigger_error("Impossible de mettre des données dans memcached");
            setcookie ("login", htmlspecialchars($u->login), strtotime( '+1 days' ),"/");
            setcookie ("keyconnexion", htmlspecialchars($u->keyconnexion), strtotime( '+1 days' ),"/");
            header ("Location: ".BASE_URL);
            exit();
        }else{
            // header("HTTP/1.1 307 Temporary Redirect");
            $this->set(array(
                "login"=>$_POST["login"],
                "erreur"=> true

            ));
            $this->render("index");
        }
    }

    function mdpoublier(){
        if ( isset ($_POST["mail"] )){
            $u = \model\mysql\Utilisateur::getUtilisateurParMail($_POST["mail"]);
            if ($u){
                do{
                    $mdp = \model\simple\String::random(8);
                }while ( preg_match('/(?=^.{8,}$)((?=.*\d)|(?=.*\W+))(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/',$mdp) != 1);

                $args["login"]= $u->login;
                $args["mdp"]= $mdp;
                $t = \model\mysql\Ticket::savTicket(__CLASS__,"modifierMdp",$args);
                $this->set(array(
                    "succereinitialmdp"=> true

                ));
                $this->render("index");
            }else{
                $this->set(array(
                    "erreur"=> true
                ));
            }
        }
    }

    function modifierMdp($login,$mdp){
        if ( isset($login)&& isset($mdp)){
            $res = \model\mysql\Utilisateur::modifierMotDePasse($login,$mdp);
            $this->set("modifiermdp",$res);
            $this->render("index");
            return $res;

        }else{
            header ("Location: ".BASE_URL);
            exit();
        }
    }

}