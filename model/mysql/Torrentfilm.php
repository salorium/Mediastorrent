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
    public $qualite;
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
        $tofilm->id = \model\simple\ChaineCaractere::random(10);

        $tofilm->insert();
        return $tofilm;
    }

    static function deleteByClefunique($clefunique)
    {
        $query = "select distinct idfilm as idfilm from torrentfilm ";
        $query .= "where clefunique=" . \core\Mysqli::real_escape_string_html($clefunique);
        \core\Mysqli::query($query);
        $tfs = \core\Mysqli::getObjectAndClose(true);
        $query = "delete  from torrentfilm ";
        $query .= "where clefunique=" . \core\Mysqli::real_escape_string_html($clefunique);
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() > 1);
        \core\Mysqli::close();
        foreach ($tfs as $k => $tf) {
            $query = "select count(*) as cpt from torrentfilm ";
            $query .= "where idfilm=" . \core\Mysqli::real_escape_string_html($tf->idfilm);
            \core\Mysqli::query($query);
            $re = \core\Mysqli::getObjectAndClose();
            if ($re->cpt === "0") {
                $res &= Film::delete($tf->idfilm);
            }
        }
        return $res;
    }

    public function insert()
    {
        if (is_null($this->id) || is_null($this->numfile) || is_null($this->idfilm) || is_null($this->login) || is_null($this->nomrtorrent) || is_null($this->hashtorrent) || is_null($this->fini) || is_null($this->partageamis) || is_null($this->clefunique))
            return false;
        $query = "insert into torrentfilm (id,date,numfile,complementfichier,idfilm,login,nomrtorrent,hashtorrent,clefunique,fini,mediainfo,qualite,partageamis) values(";
        $query .= \core\Mysqli::real_escape_string_html($this->id) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->date) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->numfile) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->complementfichier) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->idfilm) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->login) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->nomrtorrent) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->hashtorrent) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->clefunique) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->fini) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->mediainfo) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->qualite) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->partageamis) . ")";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;
    }

    static function rechercheParNumFileHashClefunique($numfile, $hash, $clefunique)
    {
        $query = "select * from torrentfilm ";
        $query .= "where clefunique=" . \core\Mysqli::real_escape_string_html($clefunique);
        $query .= " and hashtorrent=" . \core\Mysqli::real_escape_string_html($hash);
        $query .= " and numfile=" . \core\Mysqli::real_escape_string_html($numfile);
        $query .= " and fini= false";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);
    }

    /**
     * Retourne le film d'un user les seins + ceux que c'est amis lui partage
     * id
     */
    static function getFilmUserDuServeur($id)
    {
        $query = "select tf.numfile as numfile, tf.complementfichier as complementfichier,tf.hashtorrent as hash,rs.login as userscgi,f.titre as titre, tf.mediainfo as mediainfo ";
        $query .= "from torrentfilm tf, film f,rtorrent r,rtorrents rs ";
        $query .= "where( tf.fini = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and tf.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login);
        $query .= " and rs.nomrtorrent = r.nom ";
        $query .= " and rs.login = tf.login ";
        $query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= ") or (";
        $query .= "tf.fini = true ";
        $query .= "and tf.partageamis = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and rs.login = tf.login ";
        $query .= "and rs.nomrtorrent = r.nom ";
        $query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= " and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ")";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);
    }

    static function getFilmDuServeur($id)
    {
        $query = "select tf.numfile as numfile, tf.complementfichier as complementfichier,tf.hashtorrent as hash,rs.login as userscgi,f.titre as titre, tf.mediainfo as mediainfo ";
        $query .= "from torrentfilm tf, film f,rtorrent r,rtorrents rs ";
        $query .= "where tf.fini = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= " and rs.nomrtorrent = r.nom ";
        $query .= " and rs.login = tf.login ";
        $query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string_html($id);
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
        $query .= "and tf.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login);
        $query .= " and rs.nomrtorrent = r.nom ";
        $query .= "and rs.login = tf.login ";
        //$query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= ") or (";
        $query .= "tf.fini = true ";
        $query .= "and tf.partageamis = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and rs.nomrtorrent = r.nom ";
        $query .= "and rs.login = tf.login ";
        //$query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= " and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ")";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);
    }

    static function getTorrentFilmParIdFilmFini($id)
    {
        $query = "select tf.id as id,r.hostname as hostname,tf.mediainfo as mediainfo, tf.qualite as qualite, tf.complementfichier as complementfichier ";
        $query .= "from torrentfilm tf,film f,rtorrent r,rtorrents rs ";
        $query .= "where( tf.fini = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and tf.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login);
        $query .= " and rs.nomrtorrent = r.nom ";
        $query .= " and rs.login = tf.login ";
        //$query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and f.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= ") or (";
        $query .= "tf.fini = true ";
        $query .= "and tf.partageamis = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and rs.login = tf.login ";
        $query .= "and rs.nomrtorrent = r.nom ";
        //$query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and f.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= " and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ") order by qualite DESC";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
    }

    static function getTorrentFilmParIdFilm($id)
    {
        $query = "select tf.id as id,r.hostname as hostname,tf.mediainfo as mediainfo, tf.qualite as qualite, tf.complementfichier as complementfichier, tf.fini as fini, tf.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " as proprietaire, tf.partageamis as partageamis ";
        $query .= "from torrentfilm tf,film f,rtorrent r,rtorrents rs ";
        $query .= "where( tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and tf.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login);
        $query .= " and rs.nomrtorrent = r.nom ";
        $query .= " and rs.login = tf.login ";
        //$query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and f.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= ") or (";
        //$query .= "tf.fini = true ";
        $query .= "tf.partageamis = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and rs.login = tf.login ";
        $query .= "and rs.nomrtorrent = r.nom ";
        //$query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and f.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= " and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ") order by qualite DESC";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
    }

    static function getTorrentFilmParId($id)
    {
        $query = "select tf.id as id,r.hostname as hostname,tf.mediainfo as mediainfo, tf.qualite as qualite, tf.complementfichier as complementfichier, tf.fini as fini, rs.login as userscgi, tf.hashtorrent as hash ";
        $query .= "from torrentfilm tf,rtorrent r,rtorrents rs ";
        $query .= "where( tf.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and tf.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login);
        $query .= " and rs.nomrtorrent = r.nom ";
        $query .= " and rs.login = tf.login ";
        $query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        //$query .= " and f.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= ") or (";
        //$query .= "tf.fini = true ";
        $query .= "tf.partageamis = true ";
        //$query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and rs.login = tf.login ";
        $query .= "and rs.nomrtorrent = r.nom ";
        $query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= " and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ") order by qualite DESC";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose();
    }

    static function getTorrentFilmParIdForStreamingDeUtilisateur($id)
    {
        $query = "select tf.numfile as numfile, tf.complementfichier as complementfichier,tf.hashtorrent as hash,rs.login as userscgi,f.titre as titre, r.hostname as hostname,tf.mediainfo as mediainfo ";
        $query .= "from torrentfilm tf,film f,rtorrent r,rtorrents rs ";
        $query .= "where( tf.fini = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and tf.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login);
        $query .= " and rs.nomrtorrent = r.nom ";
        $query .= " and rs.login = tf.login ";
        //$query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= ") or (";
        $query .= "tf.fini = true ";
        $query .= "and tf.partageamis = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and rs.login = tf.login ";
        $query .= "and rs.nomrtorrent = r.nom ";
        //$query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= " and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ")";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose();
    }

    static function getTorrentFilmParIdForStreaming($id)
    {
        $query = "select tf.numfile as numfile, tf.complementfichier as complementfichier,tf.hashtorrent as hash,rs.login as userscgi,f.titre as titre, r.hostname as hostname,tf.mediainfo as mediainfo ";
        $query .= "from torrentfilm tf,film f,rtorrent r,rtorrents rs ";
        $query .= "where tf.fini = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= " and rs.nomrtorrent = r.nom ";
        $query .= " and rs.login = tf.login ";
        //$query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string_html($id);
        /*$query .= ") or (";
        $query .= "tf.fini = true ";
        $query .= "and tf.partageamis = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and r.nom = tf.nomrtorrent ";
        $query .= "and rs.login = tf.login ";
        $query .= "and rs.nomrtorrent = r.nom ";
        //$query .= "and r.hostname = " . \core\Mysqli::real_escape_string_html(HOST);
        $query .= " and tf.id = " . \core\Mysqli::real_escape_string_html($id);
        $query .= " )";*/
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose();
    }

    static function getClefUnique()
    {
        do {
            $query = "select * from torrentfilm ";
            $clefunique = \model\simple\ChaineCaractere::random(10);
            $query .= "where clefunique=" . \core\Mysqli::real_escape_string_html($clefunique);
            \core\Mysqli::query($query);
        } while (!is_bool(\core\Mysqli::getObjectAndClose(false, __CLASS__)));
        return $clefunique;
    }

    static function getAll()
    {
        $query = "select * from torrentfilm ";
        $query .= "where fini= true";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true, __CLASS__);
    }
    public function fini($mediainfo)
    {
        switch ($mediainfo["typequalite"]) {
            case "HD":
                if ($mediainfo["qualite"] == "1080p" || $mediainfo["qualite"] == "1080i")
                    $this->qualite = 2;
                else
                    $this->qualite = 1;
                break;
            default:
                $this->qualite = 0;
                break;
        }
        $this->mediainfo = json_encode($mediainfo);
        $this->fini = true;
        $query = "update torrentfilm set ";
        $query .= "fini=" . \core\Mysqli::real_escape_string_html($this->fini);
        $query .= ", mediainfo=" . \core\Mysqli::real_escape_string_html($this->mediainfo);
        $query .= ", qualite=" . \core\Mysqli::real_escape_string_html($this->qualite);
        $query .= " where id=" . \core\Mysqli::real_escape_string_html($this->id);
        \core\Mysqli::query($query);
        //echo $query;
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;
    }

    static function share($id, $share)
    {
        $query = "update torrentfilm set ";
        $query .= "partageamis=" . ($share == true ? "TRUE" : "FALSE");
        $query .= " where id=" . \core\Mysqli::real_escape_string_html($id) . " and login=" . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login);
        \core\Mysqli::query($query);
        //echo $query;
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;
    }
    public function updateMediainfo($mediainfo)
    {

        $this->mediainfo = json_encode($mediainfo);

        $query = "update torrentfilm set ";
        $query .= "mediainfo=" . \core\Mysqli::real_escape_string_html($this->mediainfo);
        $query .= " where id=" . \core\Mysqli::real_escape_string_html($this->id);
        \core\Mysqli::query($query);
        //echo $query;
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;

    }
} 