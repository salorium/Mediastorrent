<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 20/06/14
 * Time: 23:32
 */

namespace model\simple;


class Serie extends \core\Model
{
    static function getBackdrop($id)
    {
        $backdrop = ROOT . DS . "cache" . DS . "serie" . DS . "backdrop";
        if (!is_dir($backdrop))
            mkdir($backdrop, 0777, true);
        if (!file_exists($backdrop . DS . $id . ".jpg")) {
            $film = \model\mysql\Serie::getBackdrop($id);
            if (is_null($film->urlbackdrop)) {
                //No poster
                $content = MyImage::nonImageAddTexte($film->titre, ROOT . DS . "font" . DS . "comic.ttf", 30);
                file_put_contents($backdrop . DS . $id . ".jpg", $content);
            } else {
                copy($film->urlbackdrop, $backdrop . DS . $id . ".jpg");
            }
        }
        return $backdrop . DS . $id . ".jpg";
    }

    static function getPoster($id)
    {
        $poster = ROOT . DS . "cache" . DS . "serie" . DS . "poster";
        if (!is_dir($poster))
            mkdir($poster, 0777, true);
        if (!file_exists($poster . DS . $id . ".jpg")) {
            $film = \model\mysql\Serie::getPoster($id);
            if (is_null($film->urlposter)) {
                //No poster
                $content = MyImage::nonImageAddTexte($film->titre, ROOT . DS . "font" . DS . "comic.ttf", 30);
                file_put_contents($poster . DS . $id . ".jpg", $content);
            } else {
                copy($film->urlposter, $poster . DS . $id . ".jpg");
            }
        }
        return $poster . DS . $id . ".jpg";
    }

    static function getBackdropSetWidth($id, $width)
    {
        $myimage = new \model\simple\MyImage(Serie::getBackdrop($id));
        return $myimage->getImageWidthFixed($width);
    }

    static function getBackdropSetHeight($id, $height)
    {
        $myimage = new \model\simple\MyImage(Serie::getBackdrop($id));
        return $myimage->getImageHeightFixed($height);
    }

    static function getPosterSetWidth($id, $width)
    {
        $myimage = new \model\simple\MyImage(Serie::getPoster($id));
        return $myimage->getImageWidthFixed($width);
    }

    static function getPosterSetHeight($id, $height)
    {
        $myimage = new \model\simple\MyImage(Serie::getPoster($id));
        return $myimage->getImageHeightFixed($height);
    }

    static function getInfosPourDownload($torrents){
        $mediainfo = json_decode($torrents->mediainfo, true);
        $compfile = "[";
        $compfile .= (strlen($torrents->complementfichier) > 0 ? $torrents->complementfichier . "." : "");
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
                $res .= $v["type"] .(isset($v["cannal"]) ? " " . $v["cannal"]:"");
                if (isset($v["lang"]))
                    $res .= " " . $v["lang"];
                $audios[] = $res;

            }

        }

        if (count($audios) > 1) {
            $au = implode(".", $audios);
            $compfile .= "." . $au;
        } else {
            $compfile .= "." . $audios[0];
        }
        $compfile .= "]";
        return [$mediainfo["filename"], $torrents->titre . " Saison " . $torrents->saison . " Épisode " . $torrents->episode . " " . $compfile];
    }
}