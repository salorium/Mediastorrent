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

    function download($id, $login = null, $keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($login, $keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if ($torrentf = \model\mysql\Torrentfilm::getFilmUser($id)) {
            $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi,
                new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "f.get_frozen_path", array($torrentf->hash, intval($torrentf->numfile))));
            if ($req->success()) {
                $filename = $req->val[0];
                if ($filename == '') {
                    $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi, array(
                        new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "d.open", $torrentf->hash),
                        new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "f.get_frozen_path", array($torrentf->hash, intval($torrentf->numfile))),
                        new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "d.close", $torrentf->hash)));
                    if ($req->success())
                        $filename = $req->val[1];
                }
                \model\simple\Download::sendFileName($filename, $torrentf->titre);
            }
            throw new \Exception("FILE NOT FOUND");
        } else {
            echo "Redirection";
        }
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