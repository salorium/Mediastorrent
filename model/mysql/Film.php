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
    public $infos;
    public $idallocine;
    public $idthemoviedb;
    public function insert(){
        if ( is_null($this->titre) ||is_null($this->titreoriginal)||is_null($this->id)||is_null($this->infos))
            return false;
        $query = "insert into film (titre,titreoriginal,id,infos,idallocine,idthemoviedb) values(";
        $query .= \core\Mysqli::real_escape_string($this->titre).",";
        $query .= \core\Mysqli::real_escape_string($this->titreoriginal).",";
        $query .= \core\Mysqli::real_escape_string($this->id).",";
        $query .= \core\Mysqli::real_escape_string($this->infos).",";
        $query .= \core\Mysqli::real_escape_string($this->idallocine).",";
        $query .= \core\Mysqli::real_escape_string($this->idthemoviedb).")";
        \core\Mysqli::query($query);
        $res =  (\core\Mysqli::nombreDeLigneAffecte() == 1 );
        \core\Mysqli::close();
        return $res;

    }
    static function ajouteFilm($titre,$titreoriginal,$infos,$idallocine=null,$idthemoviedb=null){

        if ($f = Film::checkIdallocine($idallocine) ){
            return $f;
        }else {
            $film = new Film();
            $film->titre = $titre;
            $film->titreoriginal = $titreoriginal;
            $film->infos = $infos;
            $film->idallocine = $idallocine;
            $film->idthemoviedb = $idthemoviedb;
            do{
                $film->id = \model\simple\String::random(10);
            }while ( !$film->insert());
            return $film;
        }

    }
    static function checkIdallocine ($idallocine){
        $query = "select * from film ";
        $query .="where idallocine=".\core\Mysqli::real_escape_string($idallocine);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false,__CLASS__);

    }
} 