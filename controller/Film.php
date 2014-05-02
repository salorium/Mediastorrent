<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 01/05/14
 * Time: 00:59
 */

namespace controller;


class Film extends \core\Controller
{
    function recherche($login = null, $keyconnexion = null, $re = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($login, $keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if (is_null($re))
            $re = $_REQUEST["recherche"];

        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "localfilm" => \model\mysql\Film::rechercheFormat($re),
            "film" => $all->retourneResMoviesFormat()
        ));
    }

    function getInfosFilm($login, $keyconnexion, $code, $all = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($login, $keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if (is_null($all)) {
            $res = \model\mysql\Film::getByIdFormat($code);
        } else {
            $o["typesearch"] = "movie";
            $all = new \model\simple\Allocine($code, $o);
            $res = $all->retourneResMovieFormat();
        }

        $this->set(array(
            "film" => $res
        ));
    }
} 