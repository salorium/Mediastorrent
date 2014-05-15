<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 15/05/14
 * Time: 15:10
 */

namespace model\mysql;


class Cronroot
{
    public $id;
    public $donnee;
    public $resultat;
    public $fini;

    function __construct()
    {
    }

    public function insert()
    {
        $query = "insert into cronroot (id,donnee,resultat,fini) values(";
        $query .= \core\Mysqli::real_escape_string($this->id) . ", ";
        $query .= \core\Mysqli::real_escape_string($this->donnee) . ", ";
        $query .= \core\Mysqli::real_escape_string($this->resultat) . ", ";
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

    public static function sav($classe, $fonction, $args)
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
        $t->donnee = $data;
        $t->fini = false;
        return ($t->insert() ? $id : false);
    }

    public static function traiteTicket($id)
    {
        $query = "select * from ticket ";
        $query .= " where id=" . \core\Mysqli::real_escape_string($id);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(false, __CLASS__);
    }
} 