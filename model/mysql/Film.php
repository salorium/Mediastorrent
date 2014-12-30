<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 24/05/14
 * Time: 15:08
 */

namespace model\mysql;


class Film extends \core\ModelMysql
{
    public $titre;
    public $titreoriginal;
    public $id;
    public $acteurs;
    public $realisateurs;
    public $anneeprod;
    public $infos;
    public $urlposter;
    public $urlbackdrop;
    public $idallocine;
    public $idthemoviedb;

    public function insert()
    {
        if (is_null($this->titre) || is_null($this->titreoriginal) || is_null($this->id) || is_null($this->infos) || is_null($this->urlbackdrop) || is_null($this->urlposter) || is_null($this->acteurs) || is_null($this->realisateurs) || is_null($this->anneeprod))
            return false;
        $query = "insert into film (titre,titreoriginal,id,infos,idallocine,idthemoviedb,urlposter,urlbackdrop,acteurs,realisateurs,anneeprod) values(";
        $query .= \core\Mysqli::real_escape_string_html($this->titre) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->titreoriginal) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->id) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->infos) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->idallocine) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->idthemoviedb) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->urlposter) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->urlbackdrop) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->acteurs) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->realisateurs) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->anneeprod) . ")";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;

    }

    public function addGenre($genre)
    {
        $g = new Genre();
        $g->id = $this->id;
        if (is_array($genre)) {
            foreach ($genre as $k => $v) {
                $g->label = $v;
                $g->insert();
            }
        } else {
            $g->label = $genre;
            $g->insert();
        }
    }

    static function delete($id)
    {
        $query = "delete  from film ";
        $query .= "where id=" . \core\Mysqli::real_escape_string_html($id);
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() > 1);
        \core\Mysqli::close();
        return $res;
    }

    static function ajouteFilm($titre, $titreoriginal, $infos, $urlposter, $urlbackdrop, $anneeprod, $acteurs, $realisateurs, $idallocine = null, $idthemoviedb = null)
    {

        if ($f = Film::checkIdallocine($idallocine)) {
            return $f;
        } else {
            $film = new Film();
            $film->titre = $titre;
            $film->titreoriginal = $titreoriginal;
            $film->infos = $infos;
            $film->idallocine = $idallocine;
            $film->idthemoviedb = $idthemoviedb;
            $film->urlbackdrop = $urlbackdrop;
            $film->urlposter = $urlposter;
            $film->acteurs = $acteurs;
            $film->anneeprod = $anneeprod;
            $film->realisateurs = $realisateurs;
            do {
                $film->id = \model\simple\String::random(10);
            } while (!$film->insert());
            return $film;
        }

    }

    static function rechercheFormat($titre)
    {
        $query = "select titre, titreoriginal as originaltitre, id as code, urlposter as image, realisateurs as realisateur, acteurs as acteur, anneeprod from film ";
        $query .= "where titre like " . \core\Mysqli::real_escape_stringlike($titre) . " or titreoriginal like " . \core\Mysqli::real_escape_stringlike($titre);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true, __CLASS__);
    }

    static function  getByIdFormat($id)
    {
        $query = "select infos, id as code, urlposter as imageposter, urlbackdrop as imagebackdrop from film ";
        $query .= "where id=" . \core\Mysqli::real_escape_string_html($id);
        \core\Mysqli::query($query);
        $obj = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        $res = json_decode($obj->infos, true);
        $res['imageposter'] = $obj->imageposter;
        $res['imagebackdrop'] = $obj->imagebackdrop;
        $res['code'] = $obj->code;
        return $res;
    }

    static function checkIdallocine($idallocine)
    {
        $query = "select * from film ";
        $query .= "where idallocine=" . \core\Mysqli::real_escape_string_html($idallocine);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);

    }

    static function getBackdrop($id)
    {
        $query = "select urlbackdrop, titre from film ";
        $query .= "where id=" . \core\Mysqli::real_escape_string_html($id);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false);
    }

    static function getPoster($id)
    {
        $query = "select urlposter, titre from film ";
        $query .= "where id=" . \core\Mysqli::real_escape_string_html($id);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false);
    }

    static function getAllFilmUserDateDesc($genre)
    {
        $query = "select tf.date as date, f.id as id, f.urlposter as poster, f.urlbackdrop as backdrop , f.infos as infos ";
        $query .= "from torrentfilm tf, film f , genre g ";
        $query .= "where ( ";
        $query .= "tf.idfilm = f.id ";
        $query .= "and f.id = g.id ";
        if (!is_null($genre))
            $query .= "and g.label = " . \core\Mysqli::real_escape_string_html($genre);
        //$query .= "and r.nom = tf.nomrtorrent ";
        $query .= " and tf.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login);
        //$query .= " and rs.nomrtorrent = r.nom ";
        $query .= "and tf.date = (
            select max(tf1.date) from torrentfilm tf1 where tf1.idfilm = tf.idfilm
        )";
        $query .= " ) or ( ";
        //$query .= "tf.fini = true ";
        $query .= "tf.partageamis = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and f.id = g.id ";
        if (!is_null($genre))
            $query .= "and g.label = " . \core\Mysqli::real_escape_string_html($genre);
        //$query .= "and r.nom = tf.nomrtorrent ";
        //$query .= "and rs.nomrtorrent = r.nom ";
        $query .= "and tf.date = (
            select max(tf1.date) from torrentfilm tf1 where tf1.idfilm = tf.idfilm
        )";
        $query .= " and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ") GROUP BY id ORDER BY date DESC";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
    }

    static function getAllFilmUserTitreAsc($genre)
    {
        $query = "select distinct * from ( select f.titre as titre, f.id as id, f.urlposter as poster, f.urlbackdrop as backdrop , f.infos as infos ";
        $query .= "from torrentfilm tf, film f , genre g ";
        $query .= "where ( ";
        $query .= "tf.idfilm = f.id ";
        $query .= "and f.id = g.id ";
        if (!is_null($genre))
            $query .= "and g.label = " . \core\Mysqli::real_escape_string_html($genre);
        //$query .= "and r.nom = tf.nomrtorrent ";
        $query .= " and tf.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login);
        //$query .= " and rs.nomrtorrent = r.nom ";
        $query .= " ) or ( ";
        //$query .= "tf.fini = true ";
        $query .= "tf.partageamis = true ";
        $query .= "and tf.idfilm = f.id ";
        $query .= "and f.id = g.id ";
        if (!is_null($genre))
            $query .= "and g.label = " . \core\Mysqli::real_escape_string_html($genre);
        //$query .= "and r.nom = tf.nomrtorrent ";
        //$query .= "and rs.nomrtorrent = r.nom ";
        $query .= " and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ") ) t ORDER BY titre ASC";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
    }
} 