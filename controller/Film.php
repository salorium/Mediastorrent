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
    function getTime($keyconnexion, $idtorrentfilm)
    {
        \model\simple\Utilisateur::authentificationDistante($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $tf = \model\mysql\Torrentfilm::getTorrentFilmParId($idtorrentfilm);
        $tf->mediainfo = json_decode($tf->mediainfo);
        if (is_null($tf)) throw new \Exception("Id incorrect");

        if ($tf->fini === "0") {

            $cmds = array(
                "d.name" /*5*/, "d.down.rate" /*13*/, "d.size_chunks" /*8*/, "d.completed_chunks" /*7*/, "d.chunk_size" /*14*/
            );

            $req = new \model\xmlrpc\rXMLRPCRequest($tf->userscgi);
            foreach ($cmds as $v) {
                $req->addCommand(new \model\xmlrpc\rXMLRPCCommand($tf->userscgi, $v, $tf->hash));
            }


            if ($req->success()) {
                $tf->nomtorrent = $req->val[0];
                $get_completed_chunks = $req->val[3];
                $get_size_chunks = $req->val[2];
                $get_chunk_size = $req->val[4];
                $tf->timerestant = ($req->val[1] > 0 ? floor(($get_size_chunks - $get_completed_chunks) * $get_chunk_size / $req->val[1]) : -1); //Eta 9 (Temps restant en seconde)

            }
        }
        $this->set("file", $tf);
    }

    function recherche($keyconnexion = null, $re = null)
    {
        \model\simple\Utilisateur::authentificationDistante($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if (is_null($re))
            $re = $_REQUEST["recherche"];
        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "localfilm" => \model\mysql\Film::rechercheFormat($re),
            "film" => $all->retourneResMoviesFormat()
        ));
    }

    function download($id, $keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationDistante($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if ( is_string(\config\Conf::$user["user"])){
            //Traitement du ticket
            $torrentf = \model\mysql\Torrentfilm::getFilmDuServeur($id);
        }else{
            $torrentf = \model\mysql\Torrentfilm::getFilmUserDuServeur($id);
        }
        if ($torrentf ) {
            /*

             \config\Conf::$userscgi = $torrentf->userscgi;
            $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi,
                new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "f.frozen_path", array($torrentf->hash . ":f" . $torrentf->numfile)));
            if ($req->success()) {
                $filename = $req->val[0];
                if ($filename == '') {
                    $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi, array(
                        new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.open", $torrentf->hash),
                        new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "f.frozen_path", array($torrentf->hash . ":f" . $torrentf->numfile)),
                        new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.close", $torrentf->hash)));
                    if ($req->success())
                        $filename = $req->val[1];
                }
            */

                $mediainfo = json_decode($torrentf->mediainfo, true);
                $compfile = "[";
                $compfile .= (strlen($torrentf->complementfichier) > 0 ? $torrentf->complementfichier . "." : "");
                switch ($mediainfo["typequalite"]) {
                    case "SD":
                        $compfile .= $mediainfo["codec"];
                        break;
                    case "HD":
                        $compfile .= $mediainfo["qualite"] . "." . $mediainfo["codec"];
                        break;
                }
                $audios = array();
                foreach ($mediainfo["audios"] as $v) {
                    $res = "";
                    if ($v["type"] !== "MP3") {
                        $res .= $v["type"] . " " . $v["cannal"];
                        if (isset($v["lang"]))
                            $res .= " " . $v["lang"];

                    }
                    $audios[] = $res;
                }

                if (count($audios) > 1) {
                    $au = implode(".", $audios);
                    $compfile .= "." . $au . "]";
                } else {
                    $compfile .= "." . $audios[0] . "]";
                }
            $tmp = \model\simple\Download::sendFileName($mediainfo["filename"], $torrentf->titre . " " . $compfile);
            //}

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

    function getInfosFilm($code, $keyconnexion, $all = "")
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if ($all === "") {
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