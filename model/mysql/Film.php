<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 30/04/14
 * Time: 03:41
 */

namespace model\mysql;


class Film extends \core\Model{
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
    public function insert(){
        if ( is_null($this->titre) ||is_null($this->titreoriginal)||is_null($this->id)||is_null($this->infos) || is_null($this->urlbackdrop)|| is_null($this->urlposter)|| is_null($this->acteurs)|| is_null($this->realisateurs)|| is_null($this->anneeprod))
            return false;
        $query = "insert into film (titre,titreoriginal,id,infos,idallocine,idthemoviedb,urlposter,urlbackdrop,acteurs,realisateurs,anneeprod) values(";
        $query .= \core\Mysqli::real_escape_string($this->titre).",";
        $query .= \core\Mysqli::real_escape_string($this->titreoriginal).",";
        $query .= \core\Mysqli::real_escape_string($this->id).",";
        $query .= \core\Mysqli::real_escape_string($this->infos).",";
        $query .= \core\Mysqli::real_escape_string($this->idallocine).",";
        $query .= \core\Mysqli::real_escape_string($this->idthemoviedb).",";
        $query .= \core\Mysqli::real_escape_string($this->urlposter).",";
        $query .= \core\Mysqli::real_escape_string($this->urlbackdrop).",";
        $query .= \core\Mysqli::real_escape_string($this->acteurs).",";
        $query .= \core\Mysqli::real_escape_string($this->realisateurs).",";
        $query .= \core\Mysqli::real_escape_string($this->anneeprod).")";
        \core\Mysqli::query($query);
        $res =  (\core\Mysqli::nombreDeLigneAffecte() == 1 );
        \core\Mysqli::close();
        return $res;

    }
    public function addGenre($genre){
        $g = new Genre();
        $g->id = $this->id;
        if ( is_array($genre)){
            foreach ( $genre as $k=>$v){
                $g->label = $v;
                $g->insert();
            }
        }else{
            $g->label = $genre;
            $g->insert();
        }
    }
    static function ajouteFilm($titre,$titreoriginal,$infos,$urlposter,$urlbackdrop,$anneeprod,$acteurs,$realisateurs,$idallocine=null,$idthemoviedb=null){

        if ($f = Film::checkIdallocine($idallocine) ){
            return $f;
        }else {
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
            do{
                $film->id = \model\simple\String::random(10);
            }while ( !$film->insert());
            return $film;
        }

    }
    static function rechercheFormat ( $titre){
        $query = "select titre, titreoriginal as originaltitre, id as code, urlposter as image, realisateurs as realisateur, acteurs as acteur, anneeprod from film ";
        $query .="where titre like ".\core\Mysqli::real_escape_stringlike($titre)." or titreoriginal like ".\core\Mysqli::real_escape_stringlike($titre);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true,__CLASS__);
    }
    static function  getByIdFormat ( $id){
        $query = "select infos, id as code, urlposter as imageposter, urlbackdrop as imagebackdrop from film ";
        $query .="where id=".\core\Mysqli::real_escape_string($id);
        \core\Mysqli::query($query);
        $obj = \core\Mysqli::getObjectAndClose(false,__CLASS__);
        $res = json_decode($obj->infos,true);
        $res['imageposter']= $obj->imageposter;
        $res['imagebackdrop']= $obj->imagebackdrop;
        $res['code']= $obj->code;
        return $res;
    }
    static function checkIdallocine ($idallocine){
        $query = "select * from film ";
        $query .="where idallocine=".\core\Mysqli::real_escape_string($idallocine);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false,__CLASS__);

    }
} 