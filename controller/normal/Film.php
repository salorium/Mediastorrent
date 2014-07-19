<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 22/03/14
 * Time: 03:35
 */

namespace controller\normal;


use core\Controller;

class Film extends Controller
{
    public $layout = "connectermediastheque";

    function nouveau($genre = null)
    {
        $a = \model\mysql\Film::getAllFilmUserDateDesc($genre);
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
            "image" => \model\simple\Film::getBackdropSetWidth($id, $size)
        ));
        $this->render("index");
    }

    function getBackdropSetHeight($id, $size)
    {
        $this->set(array(
            "id" => $id,
            "size" => $size,
            "image" => \model\simple\Film::getBackdropSetHeight($id, $size)
        ));
        $this->render("index");
    }

    function getPosterSetWidth($id, $size)
    {
        $this->set(array(
            "id" => $id,
            "size" => $size,
            "image" => \model\simple\Film::getPosterSetWidth($id, $size)
        ));
        $this->render("index");
    }

    function getPosterSetHeight($id, $size)
    {
        $this->set(array(
            "id" => $id,
            "size" => $size,
            "image" => \model\simple\Film::getPosterSetHeight($id, $size)
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
        if ($torrentf = \model\mysql\Torrentfilm::getTorrentFilmParIdForStreaming($id)) {
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
                $str = str_replace("'", "\'", str_replace("&lt;", "<", ($torrentf->titre . " " . $compfile . "." . pathinfo($filename, PATHINFO_EXTENSION))));

                $str = htmlentities($str, ENT_NOQUOTES, "UTF-8");

                // remplacer les entités HTML pour avoir juste le premier caractères non accentués
// Exemple : "&ecute;" => "e", "&Ecute;" => "E", "Ã " => "a" ...
                $str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);

// Remplacer les ligatures tel que : Œ, Æ ...
// Exemple "Å“" => "oe"
                $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
// Supprimer tout le reste
                $str = preg_replace('#&[^;]+;#', '', $str);

                $this->set(array(
                    "titre" => $torrentf->titre . " " . $compfile,
                    "src" => "http://" . $torrentf->hostname . "/film/download/" . $id . "/" . \config\Conf::$user["user"]->keyconnexion . "/" . ($str)
                ));
            }
        }
    }

    function genre($genre = null)
    {
        $a = \model\mysql\Film::getAllFilmUserTitreAsc($genre);
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

    function getTime($idtorrentfilm)
    {
        $tf = \model\mysql\Torrentfilm::getTorrentFilmParId($idtorrentfilm);
        if (is_null($tf)) throw new \Exception("Id incorrect");
        var_dump($tf->fini);
        die();
        if ($tf->fini === 0) {

            $cmds = array(
                "d.get_name=" /*5*/, "d.get_down_rate=" /*13*/, "d.get_size_chunks=" /*8*/, "d.get_completed_chunks=" /*7*/, "d.get_chunk_size=" /*14*/
            );
            $cmd = new \model\xmlrpc\rXMLRPCCommand($tf->scgi, "d.multicall", "main");
            $res = array();
            foreach ($cmds as $v) {
                $res[] = \model\xmlrpc\rTorrentSettings::getCmd($tf->scgi, $v);
            }
            $cmd->addParameters($res);
            $req = new \model\xmlrpc\rXMLRPCRequest($tf->scgi, $cmd);

            if ($req->success()) {
                var_dump($req->val);
            }
        }
        $this->set("file", $tf);
        $this->set('rt', \model\xmlrpc\rXMLRPCRequest::$query);
    }

    function getFile($id)
    {
        $a = \model\mysql\Torrentfilm::getTorrentFilmParIdFilm($id);
        $tmp = array();
        foreach ($a as $v) {
            $v->mediainfo = json_decode($v->mediainfo);
            $tmp[] = $v;
        }
        $this->set("file", $tmp);
    }

} 