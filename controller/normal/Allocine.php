<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 13/04/14
 * Time: 16:08
 */

namespace controller\normal;


use core\Controller;

class Allocine extends Controller {
    function recherche($re){
        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "film" => $all->retourneResMoviesFormat(),
            "serie" => $all->retourneResSeriesFormat()

        ));
    }
    function rechercheFilm($re){
        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "film" => $all->retourneResMoviesFormat()
        ));
    }
    function rechercheSerie($re){
        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "serie" => $all->retourneResSeriesFormat()
        ));
    }
    function getInfosSerie($id){
        $o["typesearch"]="tvseries";
        $all = new \model\simple\Allocine($id,$o);
        $this->set(array(
            "serie" => $all->retourneResSerieFormat()
        ));
    }
    function getInfosFilm($id){
        $o["typesearch"]="movie";
        $all = new \model\simple\Allocine($id,$o);
        $this->set(array(
            "film" => $all->retourneResMovieFormat()
        ));
    }
} 