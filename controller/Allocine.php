<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 13/04/14
 * Time: 16:08
 */

namespace controller;


use core\Controller;

class Allocine extends Controller
{
    function recherche($re = null, $keyconnexion = null)
    {
        if (is_null($re))
            $re = $_REQUEST["recherche"];
        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "film" => $all->retourneResMoviesFormat(),
            "serie" => $all->retourneResSeriesFormat()

        ));
    }

    function rechercheFilm($re = null, $keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if (is_null($re))
            $re = $_REQUEST["recherche"];
        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "film" => $all->retourneResMoviesFormat()
        ));
    }

    function rechercheSerie($re = null)
    {
        if (is_null($re))
            $re = $_REQUEST["recherche"];
        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "serie" => $all->retourneResSeriesFormat()
        ));
    }

    function getInfosSerieF($id = null)
    {
        if (is_null($id))
            $id = $_REQUEST["id"];
        $o["typesearch"] = "tvseries";
        $all = new \model\simple\Allocine($id, $o);
        $this->set(array(
            "serie" => $all->retourneResSerieFormat()
        ));
    }
    function getInfosSerie($id = null)
    {
        if (is_null($id))
            $id = $_REQUEST["id"];
        $o["typesearch"] = "tvseries";
        $all = new \model\simple\Allocine($id, $o);
        $this->set(array(
            "serie" => "dd"//$all->retourneResSerie()
        ));
    }

    function getInfosFilm($id = null, $keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if (is_null($id))
            $id = $_REQUEST["id"];
        $o["typesearch"] = "movie";
        $all = new \model\simple\Allocine($id, $o);
        $this->set(array(
            "film" => $all->retourneResMovieFormat()
        ));
    }
} 