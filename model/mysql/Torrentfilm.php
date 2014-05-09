<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 02/05/14
 * Time: 05:57
 */

namespace model\mysql;


class Torrentfilm extends \core\ModelMysql
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

    static function rechercheParNumFileHashClefunique($numfile, $hash, $clefunique)
    {
        $query = "select * from torrentfilm ";
        $query .= "where clefunique=" . \core\Mysqli::real_escape_string($clefunique);
        $query .= " and hashtorrent=" . \core\Mysqli::real_escape_string($hash);
        $query .= " and numfile=" . \core\Mysqli::real_escape_string($numfile);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);
    }

    /**
     * Retourne le film d'un user les seins + ceux que c'est amis lui partage
     * id
     */
    static function getFilmUserDuServeur($id)
    {
        $query = "select tf.numfile as numfile, tf.complementfichier as complement,tf.hashtorrent as hash,rs.portscgi as portscgi,f.titre as titre, tf.mediainfo as mediainfo ";
        $query .= "from torrentfilm tf, film f,rtorrent r,rtorrents rs ";
        $query .= "where( tf.fini = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and tf.login = " . \core\Mysqli::real_escape_string(\config\Conf::$user["user"]->login);
        $query .= " and rs.nomrtorrent = r.nom ";
        $query .= " and rs.login = tf.login ";
        $query .= "and r.hostname = " . \core\Mysqli::real_escape_string(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string($id);
        $query .= ") or (";
        $query .= "tf.fini = true ";
        $query .= "and tf.partageamis = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and rs.login = tf.login ";
        $query .= "and rs.nomrtorrent = r.nom ";
        $query .= "and r.hostname = " . \core\Mysqli::real_escape_string(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string($id);
        $query .= " and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ")";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);
    }

    static function getAdresseServeurFilmUser($id)
    {
        $query = "select r.hostname as hostname ";
        $query .= "from torrentfilm tf, film f,rtorrent r,rtorrents rs ";
        $query .= "where( tf.fini = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and tf.login = " . \core\Mysqli::real_escape_string(\config\Conf::$user["user"]->login);
        $query .= " and rs.nomrtorrent = r.nom ";
        $query .= "and rs.login = tf.login ";
        //$query .= "and r.hostname = " . \core\Mysqli::real_escape_string(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string($id);
        $query .= ") or (";
        $query .= "tf.fini = true ";
        $query .= "and tf.partageamis = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and rs.nomrtorrent = r.nom ";
        $query .= "and rs.login = tf.login ";
        //$query .= "and r.hostname = " . \core\Mysqli::real_escape_string(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string($id);
        $query .= " and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ")";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);
    }

    static function getAllFilmUserDateDesc()
    {
        $query = "select distinct * from ( select f.titre as titre , f.urlposter as poster, f.urlbackdrop as backdrop ";
        $query .= "from torrentfilm tf, film f ";
        $query .= "where ( ";
        $query .= "tf.idfilm = f.id ";
        //$query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and tf.login = " . \core\Mysqli::real_escape_string(\config\Conf::$user["user"]->login);
        //$query .= " and rs.nomrtorrent = r.nom ";
        $query .= " ) or ( ";
        //$query .= "tf.fini = true ";
        $query .= "tf.partageamis = true ";
        $query .= "and tf.idfilm = f.id ";
        //$query .= "and r.nom = tf.nomrtorrent ";
        //$query .= "and rs.nomrtorrent = r.nom ";
        $query .= "and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ") ORDER BY tf.date DESC ) t";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
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

    public function fini()
    {
        $this->fini = true;
        $query = "update torrentfilm set ";
        $query .= "fini=" . \core\Mysqli::real_escape_string($this->fini);
        $query .= ", mediainfo=" . \core\Mysqli::real_escape_string($this->mediainfo);
        $query .= " where id=" . \core\Mysqli::real_escape_string($this->id);
        \core\Mysqli::query($query);
        echo $query;
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;
    }
} 