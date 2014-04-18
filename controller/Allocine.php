<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 13/04/14
 * Time: 16:08
 */

namespace controller;


use core\Controller;

class Allocine extends Controller {
    function recherche($login=null,$keyconnexion=null,$re=null){
        if (is_null($re))
            $re = $_REQUEST["recherche"];
        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "film" => $all->retourneResMoviesFormat(),
            "serie" => $all->retourneResSeriesFormat()

        ));
    }
    function rechercheFilm($login=null,$keyconnexion=null,$re=null){
        if (!is_null($login) && ! is_null($keyconnexion)){
            $u = \core\Memcached::value($login,"user");
            if ( is_null($u)){
                $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($login,$keyconnexion);
                if ( $u)
                    \core\Memcached::value($u->login,"user",$u,60*5);
            }else{
                $u = $u->keyconnexion ===$keyconnexion ? $u:false ;
                if ( is_bool($u)){
                    $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($login,$keyconnexion);
                    if ( $u)
                        \core\Memcached::value($u->login,"user",$u,60*5);
                }
                $u = $u->keyconnexion ===$keyconnexion ? $u:false ;
            }
            \config\Conf::$user["user"]= $u;
        }
        if (is_null($re))
            $re = $_REQUEST["recherche"];
        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "film" => $all->retourneResMoviesFormat()
        ));
    }
    function rechercheSerie($re=null){
        if (is_null($re))
            $re = $_REQUEST["recherche"];
        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "serie" => $all->retourneResSeriesFormat()
        ));
    }
    function getInfosSerie($id=null){
        if ( is_null($id))
            $id = $_REQUEST["id"];
        $o["typesearch"]="tvseries";
        $all = new \model\simple\Allocine($id,$o);
        $this->set(array(
            "serie" => $all->retourneResSerieFormat()
        ));
    }
    function getInfosFilm($id=null){
        if ( is_null($id))
            $id = $_REQUEST["id"];
        $o["typesearch"]="movie";
        $all = new \model\simple\Allocine($id,$o);
        $this->set(array(
            "film" => $all->retourneResMovieFormat()
        ));
    }
} 