<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 22/03/14
 * Time: 03:35
 */

namespace controller\normal;


use core\Controller;

class Serie extends Controller
{
    public $layout = "connectermediastheque";

    function nouveau($genre = null)
    {
        $a = \model\mysql\Serie::getAllSerieUserDateDesc($genre);
        //var_dump(json_encode($a));
        $tmp = array();
        if (count($a) > 0)
            foreach ($a as $v) {
                $t = null;
                $t = json_decode($v->infos);
                $t->id = $v->id;
                $t->poster = $v->poster;
                $t->backdrop = $v->backdrop;
                $tmp[] = $t;
            }
        $this->set("film", $tmp);
        // die();
    }

    function getBackdropSetWidth($id, $size)
    {
        $this->set(array(
            "id" => $id,
            "size" => $size,
            "image" => \model\simple\Serie::getBackdropSetWidth($id, $size)
        ));
        $this->render("index");
    }

    function getBackdropSetHeight($id, $size)
    {
        $this->set(array(
            "id" => $id,
            "size" => $size,
            "image" => \model\simple\Serie::getBackdropSetHeight($id, $size)
        ));
        $this->render("index");
    }

    function getPosterSetWidth($id, $size)
    {
        $this->set(array(
            "id" => $id,
            "size" => $size,
            "image" => \model\simple\Serie::getPosterSetWidth($id, $size)
        ));
        $this->render("index");
    }

    function getPosterSetHeight($id, $size)
    {
        $this->set(array(
            "id" => $id,
            "size" => $size,
            "image" => \model\simple\Serie::getPosterSetHeight($id, $size)
        ));
        $this->render("index");
    }

    function imageSetHeight($url, $size)
    {
        //$url = urldecode($url);
        $myimage = new \model\simple\MyImage($url);

        $this->set(array(
            "url" => $url,
            "size" => $size,
            "image" => $myimage->getImageHeightFixed($size)
        ));
        $this->render("index");
    }

    function streaming($id)
    {
        $this->layout = "streaming";

        if (is_string(\config\Conf::$user["user"])) {
            //Traitement du ticket
            $torrentf = \model\mysql\Torrentserie::getTorrentSerieParIdForStreaming($id);
        } else {
            $torrentf = \model\mysql\Torrentserie::getTorrentSerieParIdForStreamingDeUtilisateur($id);
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
            $str = str_replace("'", "\'", str_replace("&lt;", "<", ($torrentf->titre . " " . $compfile . "." . pathinfo($mediainfo["filename"], PATHINFO_EXTENSION))));

                $str = htmlentities($str, ENT_NOQUOTES, "UTF-8");

                // remplacer les entités HTML pour avoir juste le premier caractères non accentués
// Exemple : "&ecute;" => "e", "&Ecute;" => "E", "Ã " => "a" ...
                $str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);

// Remplacer les ligatures tel que : Œ, Æ ...
// Exemple "Å“" => "oe"
                $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
// Supprimer tout le reste
                $str = preg_replace('#&[^;]+;#', '', $str);
                if (is_string(\config\Conf::$user["user"])) {
                    $idticket = \model\mysql\Ticket::savTicket("controller\\Film", "download", [$id], 60 * 60 * 6);
                }
                $this->set(array(
                    "titre" => $torrentf->titre . " " . $compfile,
                    "src" => (is_string(\config\Conf::$user["user"]) == true ? "http://" . $torrentf->hostname . "/ticket/traite/" . $idticket . "/" . ($str) : "http://" . $torrentf->hostname . "/serie/download/" . $id . "/" . \config\Conf::$user["user"]->keyconnexion . "/" . ($str))
                ));
            //}
        }
    }

    function genre($genre = null)
    {
        $a = \model\mysql\Serie::getAllSerieUserTitreAsc($genre);
        //var_dump(json_encode($a));
        $tmp = array();
        if (count($a) > 0)
            foreach ($a as $v) {
                $t = null;
                $t = json_decode($v->infos);
                $t->id = $v->id;
                $t->poster = $v->poster;
                $t->backdrop = $v->backdrop;
                $tmp[] = $t;
            }
        $this->set("film", $tmp);
    }


    function getFileParSaison($id, $saison)
    {
        $a = \model\mysql\Torrentserie::getTorrentSerieParIdSerieEtParSaison($id, $saison);
        $tmp = array();
        $tmp1 = array();
        $fini = true;
        foreach ($a as $v) {
            $v->mediainfo = json_decode($v->mediainfo);
            if ($v->fini == 0) {
                $tmp1[$v->clefunique] = [$v->hashtorrent, $v->userscgi, $v->hostname];
                $fini = false;
            }
            $tmp[] = $v;
        }
        $nbrequetelocal = 0;
        $cmds = array(
            "d.name" /*5*/, "d.down.rate" /*13*/, "d.size_chunks" /*8*/, "d.completed_chunks" /*7*/, "d.chunk_size" /*14*///"d.custom=clefunique"
        );
        $requetedistante = array();
        //$this->set("tmp1",$tmp1);
        $req = array();
        foreach ($tmp1 as $k => $v) {
            if ($v[2] === HOST) {
                //Requête local
                $nbrequetelocal++;
                if (!isset ($req[$v[1]])) {

                    $req[$v[1]][0] = new \model\xmlrpc\rXMLRPCRequest($v[1]);
                    $req[$v[1]][1] = 0;
                }
                $req[$v[1]][1]++;
                foreach ($cmds as $vv) {
                    $req[$v[1]][0]->addCommand(new \model\xmlrpc\rXMLRPCCommand($v[1], $vv, $v[0]));
                }
                $req[$v[1]][0]->addCommand(new \model\xmlrpc\rXMLRPCCommand($v[1], "d.custom", array($v[0], "clefunique")));
            } else {//*/
                //Requête distante
                //
                $requetedistante[$v[2]] = $v[2];
            }
        }

        $time = array();
        if ($nbrequetelocal > 0) {
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
            //$this->set("reqlocal",$req);
        }
        /*
         * //////////////////////
         * // Requête distante //
         * //////////////////////
         */
        $urls = array();
        $reqdistanteresponse = array();
        $json = array();
        foreach ($requetedistante as $v) {
            $url = "http://" . $v . "/serie/getTimeSerie/" . \config\Conf::$user["user"]->keyconnexion . "/" . $id . "/" . $saison . ".json";
            $urls[] = $url;
            $jsonres = json_decode(file_get_contents($url), true);
            if ($jsonres['showdebugger'] === "ok") {
                $json[] = $jsonres['time'];
                $time = array_merge($time, $jsonres['time']);
            } else {
                $reqdistanteresponse[] = $jsonres;
            }
        }
        //$this->set('jsonres',$json);
        //$this->set('requetedistantereponse',$reqdistanteresponse);
        $this->set('time', $time);
        $this->set("url", $urls);
        $this->set("file", $tmp);
        $this->set("fini", $fini);
        //$this->set("tmp1",$tmp1);

    }

    function getSaison($id)
    {
        $a = \model\mysql\Torrentserie::getSaisonTorrentSerieParIdSerie($id);

        $this->set("file", $a);
        $this->set('idserie', $id);
    }

} 