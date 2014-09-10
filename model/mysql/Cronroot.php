<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 15/05/14
 * Time: 15:10
 */

namespace model\mysql;

class Cronroot extends \core\ModelMysql
{
    public $id;
    public $donnee;
    public $resultat;
    public $nomrtorrent;
    public $encour;
    public $fini;

    function __construct()
    {
    }

    public function insert()
    {
        $query = "insert into cronroot (id,donnee,resultat,nomrtorrent,encour,fini) values(";
        $query .= \core\Mysqli::real_escape_string($this->id) . ", ";
        $query .= \core\Mysqli::real_escape_string($this->donnee) . ", ";
        $query .= \core\Mysqli::real_escape_string($this->resultat) . ", ";
        $query .= \core\Mysqli::real_escape_string($this->nomrtorrent) . ", ";
        $query .= \core\Mysqli::real_escape_string($this->nomrtorrent) . ", ";
        $query .= \core\Mysqli::real_escape_string($this->fini) . ")";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;

    }

    public function delete()
    {
        if (!is_null($this->id)) {
            $query = "delete from cronroot ";
            $query .= " where id=" . \core\Mysqli::real_escape_string($this->id);
            \core\Mysqli::query($query);
            $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
            \core\Mysqli::close();
            return $res;
        }
        return false;
    }

    public static function sav($nomrtorrent, $classe, $fonction, $args)
    {
        $data = array("classe" => $classe, "fonction" => $fonction, "args" => $args);
        $data = json_encode($data);
        $id = 0;
        do {
            $id = sha1(uniqid());
            $query = "select * from cronroot ";
            $query .= " where id=" . \core\Mysqli::real_escape_string($id);
            \core\Mysqli::query($query);
            $u = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        } while ($u);
        $t = new Cronroot();
        $t->id = $id;
        $t->nomrtorrent = $nomrtorrent;
        $t->donnee = $data;
        $t->fini = false;
        $t->encour = false;
        return ($t->insert() ? $id : false);
    }

    public static function traiteTicket($id)
    {
        $query = "select * from ticket ";
        $query .= " where id=" . \core\Mysqli::real_escape_string($id);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);
    }

    public function setEncour()
    {
        $this->encour = true;
        $query = "update cronroot set ";
        $query .= "encour=" . \core\Mysqli::real_escape_string($this->encour);
        //$query .= ", resultat=" . \core\Mysqli::real_escape_string($this->resultat);
        $query .= " where id=" . \core\Mysqli::real_escape_string($this->id) . " and encour=" . \core\Mysqli::real_escape_string(false);
        \core\Mysqli::query($query);
        //echo $query;
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;
    }

    public function setFini($resultat)
    {
        $resultat = json_encode($resultat);
        $this->fini = true;
        $this->resultat = $resultat;
        $query = "update cronroot set ";
        $query .= "fini=" . \core\Mysqli::real_escape_string($this->fini);
        $query .= ", resultat=" . \core\Mysqli::real_escape_string($this->resultat);
        $query .= " where id=" . \core\Mysqli::real_escape_string($this->id);
        \core\Mysqli::query($query);
        //echo $query;
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;
    }

    public static function getAllNonFini()
    {
        $query = "select * from cronroot ";
        $query .= " where fini=" . \core\Mysqli::real_escape_string(false) . " and nomrtorrent=" . \core\Mysqli::real_escape_string(\config\Conf::$nomrtorrent) . " and encour=" . \core\Mysqli::real_escape_string(false);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true, __CLASS__);
    }
} 