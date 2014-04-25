<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 26/04/14
 * Time: 00:49
 */

namespace controller\horsligne;


class Utilisateur extends \core\Controller {
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