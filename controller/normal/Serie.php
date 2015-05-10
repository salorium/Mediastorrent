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
            \config\Conf::$portscgi = $torrentf->portscgi;
            $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi,
                new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "f.frozen_path", array($torrentf->hash . ":f" . $torrentf->numfile)));
            if ($req->success()) {
                $filename = $req->val[0];
                if ($filename == '') {
                    $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi, array(
                        new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "d.open", $torrentf->hash),
                        new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "f.frozen_path", array($torrentf->hash . ":f" . $torrentf->numfile)),
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
                if (is_string(\config\Conf::$user["user"])) {
                    $idticket = \model\mysql\Ticket::savTicket("controller\\Film", "download", [$id], 60 * 60 * 6);
                }
                $this->set(array(
                    "titre" => $torrentf->titre . " " . $compfile,
                    "src" => (is_string(\config\Conf::$user["user"]) == true ? "http://" . $torrentf->hostname . "/ticket/traite/" . $idticket . "/" . ($str) : "http://" . $torrentf->hostname . "/serie/download/" . $id . "/" . \config\Conf::$user["user"]->keyconnexion . "/" . ($str))
                ));
            }
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


    function getFile($id)
    {
        $a = \model\mysql\Torrentserie::getTorrentSerieParIdSerie($id);
        $tmp = array();
        foreach ($a as $v) {
            $v->mediainfo = json_decode($v->mediainfo);
            $tmp[] = $v;
        }
        $this->set("file", $tmp);
    }

} 