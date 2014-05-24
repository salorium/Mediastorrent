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

    function streaming($id)
    {
        $this->layout = "streaming";
        if ($torrentf = \model\mysql\Torrentfilm::getTorrentFilmParIdFilmForStreaming($id)) {
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
                $this->set(array(
                    "src" => "http://" . $torrentf->hostname . "/film/download/" . $id . "/" . \config\Conf::$user["user"]->login . "/" . \config\Conf::$user["user"]->keyconnexion . "/" . htmlentities($torrentf->titre . " " . $compfile . "." . pathinfo($filename, PATHINFO_EXTENSION))
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

    function getFile($id)
    {
        //sleep(10);
        $a = \model\mysql\Torrentfilm::getTorrentFilmParIdFilm($id);
        $tmp = array();
        foreach ($a as $v) {
            $v->mediainfo = json_decode($v->mediainfo);
            $tmp[] = $v;
        }
        $this->set("file", $tmp);
    }

} 