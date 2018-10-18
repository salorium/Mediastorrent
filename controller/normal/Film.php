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

        if (is_string(\config\Conf::$user["user"])) {
            //Traitement du ticket
            $torrentf = \model\mysql\Torrentfilm::getTorrentFilmParIdForStreaming($id);
        } else {
            $torrentf = \model\mysql\Torrentfilm::getTorrentFilmParIdForStreamingDeUtilisateur($id);
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
                    "src" => (is_string(\config\Conf::$user["user"]) == true ? "http://" . $torrentf->hostname . "/ticket/traite/" . $idticket . "/" . ($str) : "http://" . $torrentf->hostname . "/film/download/" . $id . "/" . \config\Conf::$user["user"]->keyconnexion . "/" . ($str))
                ));
            //}
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

    function getPosters($id){
        $o = array();
        $o["typesearch"]="movie";
        $a = new \model\simple\Allocine($id,$o);
        $this->set("image",$a->retourneResMovieImagePoster());
    }
    function getBackdrops($id){
        $o = array();
        $o["typesearch"]="movie";
        $a = new \model\simple\Allocine($id,$o);
        $this->set("image",$a->retourneResMovieImageBackdrop());

    }
    function setBackdrop($id){
        $film = \model\mysql\Film::setBackdrop($id,$_REQUEST["url"]);
        $url = ROOT . DS . "cache" . DS . "film" . DS . "backdrop".DS . $id . ".jpg";
         unlink($url);
        $this->set("ok",$film);
        $this->set("path",$url);

    }

    function setPoster($id){
        $film = \model\mysql\Film::setPoster($id,$_REQUEST["url"]);
        $url = ROOT . DS . "cache" . DS . "film" . DS . "poster".DS . $id . ".jpg";
        unlink($url);
        $this->set("ok",$film);
        $this->set("path",$url);

    }
    function getFile($id)
    {
        $a = \model\mysql\Torrentfilm::getTorrentFilmParIdFilm($id);
        $tmp = array();
        foreach ($a as $v) {
            $tmp1 = $v->getFilename();
            $v->filename = $tmp1[1].".".pathinfo($tmp1[0],PATHINFO_EXTENSION);
            $v->mediainfo = json_decode($v->mediainfo);
            $tmp[] = $v;
        }
        $this->set("file", $tmp);
    }

} 