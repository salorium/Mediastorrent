<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 14/04/14
 * Time: 12:02
 */

namespace controller\normal;


use core\Controller;

class Themoviedb extends Controller
{
    /*function recherche($re){
        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "film" => $all->retourneResMoviesFormat(),
            "serie" => $all->retourneResSeriesFormat()

        ));
    }*/
    function rechercheFilm($re)
    {
        $all = new \model\simple\TheMovieDb();
        $this->set(array(
            "film" => $all->searchFilm($re)
        ));
    }

    function rechercheSerie($re)
    {
        $all = new \model\simple\TheMovieDb();
        $this->set(array(
            "serie" => $all->searchSerie($re)
        ));
    }

    function getInfosSerie($id)
    {
        $all = new \model\simple\TheMovieDb();
        $this->set(array(
            "serie" => $all->getSerie($id)
        ));
    }

    function getImageSerie($id){
        $all = new \model\simple\TheMovieDb();
        $this->set(array(
            "i"=> $all->getSerieImage(48866)
        ));
    }

    function getInfosFilm($id)
    {
        $all = new \model\simple\TheMovieDb();
        $this->set(array(
            "film" => $all->getMovie($id)
        ));
    }
} 