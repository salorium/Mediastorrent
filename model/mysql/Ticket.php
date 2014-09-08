<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 12/03/14
 * Time: 20:23
 */

namespace model\mysql;


class Ticket extends \core\ModelMysql
{
    public $id;
    public $donnee;
    public $expire;

    function __construct()
    {
    }

    public function insert()
    {
        $query = "insert into ticket (id,donnee,expire) values(";
        $query .= \core\Mysqli::real_escape_string($this->id) . ", ";
        $query .= \core\Mysqli::real_escape_string($this->donnee) . ", ";
        $query .= \core\Mysqli::real_escape_string($this->donnee) . ") ";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;

    }

    public function delete()
    {
        if (!is_null($this->id)) {
            $query = "delete from ticket ";
            $query .= " where id=" . \core\Mysqli::real_escape_string($this->id);
            \core\Mysqli::query($query);
            $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
            \core\Mysqli::close();
            return $res;
        }
        return false;
    }

    public static function savTicket($classe, $fonction, $args)
    {
        $data = array("classe" => $classe, "fonction" => $fonction, "args" => $args);
        $data = json_encode($data);
        $id = 0;
        do {
            $id = sha1(uniqid());
            $query = "select * from ticket ";
            $query .= " where id=" . \core\Mysqli::real_escape_string($id);
            \core\Mysqli::query($query);
            $u = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        } while ($u);
        $t = new Ticket();
        $t->id = $id;
        $t->donnee = $data;
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