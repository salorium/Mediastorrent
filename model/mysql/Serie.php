<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 24/05/14
 * Time: 15:08
 */

namespace model\mysql;


class Serie extends \core\ModelMysql
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
        $query = "insert into serie (titre,titreoriginal,id,infos,idallocine,idthemoviedb,urlposter,urlbackdrop,acteurs,realisateurs,anneeprod) values(";
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
        $g = new Genreserie();
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
        $query = "delete  from serie ";
        $query .= "where id=" . \core\Mysqli::real_escape_string_html($id);
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() > 1);
        \core\Mysqli::close();
        return $res;
    }

    static function ajouteSerie($titre, $titreoriginal, $infos, $urlposter, $urlbackdrop, $anneeprod, $acteurs, $realisateurs, $idallocine = null, $idthemoviedb = null)
    {

        if ($f = Serie::checkIdallocine($idallocine)) {
            return $f;
        } else {
            $serie = new Serie();
            $serie->titre = $titre;
            $serie->titreoriginal = $titreoriginal;
            $serie->infos = $infos;
            $serie->idallocine = $idallocine;
            $serie->idthemoviedb = $idthemoviedb;
            $serie->urlbackdrop = $urlbackdrop;
            $serie->urlposter = $urlposter;
            $serie->acteurs = $acteurs;
            $serie->anneeprod = $anneeprod;
            $serie->realisateurs = $realisateurs;
            do {
                $serie->id = \model\simple\ChaineCaractere::random(10);
            } while (!$serie->insert());
            return $serie;
        }

    }

    static function rechercheFormat($titre)
    {
        $query = "select titre, titreoriginal as originaltitre, id as code, urlposter as image, realisateurs as realisateur, acteurs as acteur, anneeprod from serie ";
        $query .= "where titre like " . \core\Mysqli::real_escape_stringlike($titre) . " or titreoriginal like " . \core\Mysqli::real_escape_stringlike($titre);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true, __CLASS__);
    }

    static function  getByIdFormat($id)
    {
        $query = "select infos, id as code, urlposter as imageposter, urlbackdrop as imagebackdrop from serie ";
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
        $query = "select * from serie ";
        $query .= "where idallocine=" . \core\Mysqli::real_escape_string_html($idallocine);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);

    }

    static function getBackdrop($id)
    {
        $query = "select urlbackdrop, titre from serie ";
        $query .= "where id=" . \core\Mysqli::real_escape_string_html($id);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false);
    }

    static function getPoster($id)
    {
        $query = "select urlposter, titre from serie ";
        $query .= "where id=" . \core\Mysqli::real_escape_string_html($id);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false);
    }

    static function getAllSerieUserDateDesc($genre)
    {
        $query = "select tf.date as date, f.id as id, f.urlposter as poster, f.urlbackdrop as backdrop , f.infos as infos ";
        $query .= "from torrentserie tf, serie f , genreserie g ";
        $query .= "where ( ";
        $query .= "tf.idserie = f.id ";
        $query .= "and f.id = g.id ";
        if (!is_null($genre))
            $query .= "and g.label = " . \core\Mysqli::real_escape_string_html($genre);
        //$query .= "and r.nom = tf.nomrtorrent ";
        $query .= " and tf.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login);
        //$query .= " and rs.nomrtorrent = r.nom ";
        $query .= "and tf.date = (
            select max(tf1.date) from torrentserie tf1 where tf1.idserie = tf.idserie
        )";
        $query .= " ) or ( ";
        //$query .= "tf.fini = true ";
        $query .= "tf.partageamis = true ";
        $query .= "and tf.idserie = f.id ";
        $query .= "and f.id = g.id ";
        if (!is_null($genre))
            $query .= "and g.label = " . \core\Mysqli::real_escape_string_html($genre);
        //$query .= "and r.nom = tf.nomrtorrent ";
        //$query .= "and rs.nomrtorrent = r.nom ";
        $query .= "and tf.date = (
            select max(tf1.date) from torrentserie tf1 where tf1.idserie = tf.idserie
        )";
        $query .= " and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ") GROUP BY id ORDER BY date DESC";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
    }

    static function getAllSerieUserTitreAsc($genre)
    {
        $query = "select distinct * from ( select f.titre as titre, f.id as id, f.urlposter as poster, f.urlbackdrop as backdrop , f.infos as infos ";
        $query .= "from torrentserie tf, serie f , genreserie g ";
        $query .= "where ( ";
        $query .= "tf.idserie = f.id ";
        $query .= "and f.id = g.id ";
        if (!is_null($genre))
            $query .= "and g.label = " . \core\Mysqli::real_escape_string_html($genre);
        //$query .= "and r.nom = tf.nomrtorrent ";
        $query .= " and tf.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login);
        //$query .= " and rs.nomrtorrent = r.nom ";
        $query .= " ) or ( ";
        //$query .= "tf.fini = true ";
        $query .= "tf.partageamis = true ";
        $query .= "and tf.idserie = f.id ";
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