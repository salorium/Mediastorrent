<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 02/05/14
 * Time: 05:57
 */

namespace model\mysql;


class Torrentfilm extends \core\Model
{
    public $id;
    public $date;
    public $numfile;
    public $complementfichier;
    public $idfilm;
    public $login;
    public $nomrtorrent;
    public $hashtorrent;
    public $clefunique;
    public $fini;
    public $mediainfo;
    public $partageamis;

    static function addTorrentFilm($idfilm, $numfile, $complementfichier, $login, $nomrtorrent, $hashtorrent, $clefunique, $partageamis)
    {
        $tofilm = new Torrentfilm();
        $tofilm->numfile = $numfile;
        $tofilm->complementfichier = $complementfichier;
        $tofilm->idfilm = $idfilm;
        $tofilm->login = $login;
        $tofilm->nomrtorrent = $nomrtorrent;
        $tofilm->hashtorrent = $hashtorrent;
        $tofilm->clefunique = $clefunique;
        $tofilm->fini = 0;
        $tofilm->partageamis = $partageamis;
            $tofilm->id = \model\simple\String::random(10);

        $tofilm->insert();
        return $tofilm;
    }

    static function rechercheParNumFileHashClefunique($numfile, $hash, $clefunique)
    {
        $query = "select * from torrentfilm ";
        $query .= "where clefunique=" . \core\Mysqli::real_escape_string($clefunique);
        $query .= " and hashtorrent=" . \core\Mysqli::real_escape_string($hash);
        $query .= " and numfile=" . \core\Mysqli::real_escape_string($numfile);
        echo $query;
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);
    }

    static function getClefUnique()
    {
        do {
            $query = "select * from torrentfilm ";
            $clefunique = \model\simple\String::random(10);
            $query .= "where clefunique=" . \core\Mysqli::real_escape_string($clefunique);
            \core\Mysqli::query($query);
        } while (!is_bool(\core\Mysqli::getObjectAndClose(false, __CLASS__)));
        return $clefunique;
    }

    public function insert()
    {
        if (is_null($this->id) || is_null($this->numfile) || is_null($this->idfilm) || is_null($this->login) || is_null($this->nomrtorrent) || is_null($this->hashtorrent) || is_null($this->fini) || is_null($this->partageamis) || is_null($this->clefunique))
            return false;
        $query = "insert into torrentfilm (id,date,numfile,complementfichier,idfilm,login,nomrtorrent,hashtorrent,clefunique,fini,mediainfo,partageamis) values(";
        $query .= \core\Mysqli::real_escape_string($this->id) . ",";
        $query .= \core\Mysqli::real_escape_string($this->date) . ",";
        $query .= \core\Mysqli::real_escape_string($this->numfile) . ",";
        $query .= \core\Mysqli::real_escape_string($this->complementfichier) . ",";
        $query .= \core\Mysqli::real_escape_string($this->idfilm) . ",";
        $query .= \core\Mysqli::real_escape_string($this->login) . ",";
        $query .= \core\Mysqli::real_escape_string($this->nomrtorrent) . ",";
        $query .= \core\Mysqli::real_escape_string($this->hashtorrent) . ",";
        $query .= \core\Mysqli::real_escape_string($this->clefunique) . ",";
        $query .= \core\Mysqli::real_escape_string($this->fini) . ",";
        $query .= \core\Mysqli::real_escape_string($this->mediainfo) . ",";
        $query .= \core\Mysqli::real_escape_string($this->partageamis) . ")";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;
    }
} 