<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 01/05/14
 * Time: 00:59
 */

namespace controller;


class Serie extends \core\Controller
{
    function getTime($keyconnexion, $idtorrentfilm)
    {
        \model\simple\Utilisateur::authentificationDistante($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $tf = \model\mysql\Torrentserie::getTorrentSerieParId($idtorrentfilm);
        $tf->mediainfo = json_decode($tf->mediainfo);
        if (is_null($tf)) throw new \Exception("Id incorrect");

        if ($tf->fini === "0") {

            $cmds = array(
                "d.name" /*5*/, "d.down.rate" /*13*/, "d.size_chunks" /*8*/, "d.completed_chunks" /*7*/, "d.chunk_size" /*14*/
            );

            $req = new \model\xmlrpc\rXMLRPCRequest($tf->scgi);
            foreach ($cmds as $v) {
                $req->addCommand(new \model\xmlrpc\rXMLRPCCommand($tf->scgi, $v, $tf->hash));
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

    function getTimeSerie($keyconnexion, $idserie, $saison)
    {
        \model\simple\Utilisateur::authentificationDistante($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        $tfs = \model\mysql\Torrentserie::getTorrentSerieNonFiniParIdSerieEtParSaisonDuServeur($idserie, $saison);
        $req = array();
        $cmds = array(
            "d.name" /*5*/, "d.down.rate" /*13*/, "d.size_chunks" /*8*/, "d.completed_chunks" /*7*/, "d.chunk_size" /*14*/
        );
        foreach ($tfs as $tf) {
            if (!isset ($req[$tf->userscgi])) {

                $req[$tf->userscgi][0] = new \model\xmlrpc\rXMLRPCRequest($tf->userscgi);
                $req[$tf->userscgi][1] = 0;
            }
            $req[$tf->userscgi][1]++;
            foreach ($cmds as $vv) {
                $req[$tf->userscgi][0]->addCommand(new \model\xmlrpc\rXMLRPCCommand($tf->userscgi, $vv, $tf->hashtorrent));
            }
            $req[$tf->userscgi][0]->addCommand(new \model\xmlrpc\rXMLRPCCommand($tf->userscgi, "d.custom", array($tf->hashtorrent, "clefunique")));

        }
        $time = array();
        if (count($tfs) > 0) {
            foreach ($req as $v) {
                $vreq = $v[0];
                if ($vreq->success()) {
                    for ($i = 0; $i < $v[1]; $i++) {
                        $tf = new \stdClass();
                        $tf->nomtorrent = $vreq->val[0 + $i * 6];
                        $get_completed_chunks = $vreq->val[3 + $i * 6];
                        $get_size_chunks = $vreq->val[2 + $i * 6];
                        $get_chunk_size = $vreq->val[4 + $i * 6];
                        $tf->timerestant = ($vreq->val[1 + $i * 6] > 0 ? floor(($get_size_chunks - $get_completed_chunks) * $get_chunk_size / $vreq->val[1 + $i * 6]) : -1); //Eta 9 (Temps restant en seconde)
                        $time[$vreq->val[5 + $i * 6]] = $tf;
                    }
                }
            }

        }
        $this->set('time', $time);
    }


    function recherche($keyconnexion = null, $re = null)
    {
        \model\simple\Utilisateur::authentificationDistante($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if (is_null($re))
            $re = $_REQUEST["recherche"];
        $all = new \model\simple\Allocine($re);
        $this->set(array(
            "localfilm" => \model\mysql\Serie::rechercheFormat($re),
            "film" => $all->retourneResSeriesFormat()
        ));
    }

    function download($id, $keyconnexion = null)
    {
        \model\simple\Utilisateur::authentificationDistante($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if (is_string(\config\Conf::$user["user"])) {
            //Traitement du ticket
            $torrentf = \model\mysql\Torrentserie::getSerieDuServeur($id);
        } else {
            $torrentf = \model\mysql\Torrentserie::getSerieUserDuServeur($id);
        }
        if ($torrentf) {
            /*\config\Conf::$userscgi = $torrentf->userscgi;
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
                }*/
            $infos = \model\simple\Serie::getInfosPourDownload($torrentf);
            $tmp = \model\simple\Download::sendFile($infos[0], $infos[1]);
            //}

        } else {
            if ($torrentf = \model\mysql\Torrentserie::getAdresseServeurSerieUser($id)) {
                //echo ('Location: http'.($_SERVER["SERVER_PORT"] == 80 ? "" : "s") . "://" . $torrentf->hostname."/film/download/".$id."/".\config\Conf::$user["user"]->login."/".\config\Conf::$user["user"]->keyconnexion);
                //die();
                header('Location: http' . ($_SERVER["SERVER_PORT"] == 80 ? "" : "s") . "://" . $torrentf->hostname . "/film/download/" . $id . "/" . \config\Conf::$user["user"]->login . "/" . \config\Conf::$user["user"]->keyconnexion);
                exit();
            } else {
                throw new \Exception("FILE NOT FOUND");
            }
        }
    }

    function getInfos($code, $keyconnexion, $all = "")
    {
        \model\simple\Utilisateur::authentificationPourRtorrent($keyconnexion);
        if (!\config\Conf::$user["user"]) throw new \Exception("Non User");
        if ($all === "") {
            $res = \model\mysql\Serie::getByIdFormat($code);
        } else {
            $o["typesearch"] = "tvseries";
            $all = new \model\simple\Allocine($code, $o);
            $res = $all->retourneResSerieFormat();
        }

        $this->set(array(
            "film" => $res
        ));
    }
} 