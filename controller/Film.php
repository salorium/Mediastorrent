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
        \model\simple\Utilisateur::authentificationDistante($login, $keyconnexion);
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
        \model\simple\Utilisateur::authentificationDistante($login, $keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if ($torrentf = \model\mysql\Torrentfilm::getFilmUserDuServeur($id)) {
            \config\Conf::$portscgi = $torrentf->portscgi;
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
                $mediainfo = json_decode($torrentf->mediainfo, true);
                $compfile = "";
                switch ($mediainfo["typequalite"]) {
                    case "SD":
                        $compfile .= "[" . $mediainfo["codec"];
                        break;
                    case "HD":
                        $compfile .= "[" . $mediainfo["qualite"] . "." . $mediainfo["codec"];
                        break;
                }
                $audios = array();
                foreach ($mediainfo["audios"] as $v) {
                    $res = "";
                    if ($v["type"] !== "MP3") {
                        $res .= "." . $v["type"] . " " . $v["cannal"];
                        if (isset($v["lang"]))
                            $res .= " " . $v["lang"];

                    }
                    $audios[] = $res;
                }
                if (count($audios) > 1) {
                    $compfile .= implode(" / " . $audios) . "]";
                } else {
                    $compfile .= $audios[0] . "]";
                }

                $tmp = \model\simple\Download::sendFileName($filename, $torrentf->titre . " " . $compfile);
            }

        } else {
            if ($torrentf = \model\mysql\Torrentfilm::getAdresseServeurFilmUser($id)) {
                //echo ('Location: http'.($_SERVER["SERVER_PORT"] == 80 ? "" : "s") . "://" . $torrentf->hostname."/film/download/".$id."/".\config\Conf::$user["user"]->login."/".\config\Conf::$user["user"]->keyconnexion);
                //die();
                header('Location: http' . ($_SERVER["SERVER_PORT"] == 80 ? "" : "s") . "://" . $torrentf->hostname . "/film/download/" . $id . "/" . \config\Conf::$user["user"]->login . "/" . \config\Conf::$user["user"]->keyconnexion);
                exit();
            } else {
                throw new \Exception("FILE NOT FOUND");
            }
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