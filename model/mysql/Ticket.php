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
        $query .= \core\Mysqli::real_escape_string_html($this->id) . ", ";
        $query .= \core\Mysqli::real_escape_string_html($this->donnee) . ", ";
        $query .= \core\Mysqli::dateUnixTime($this->expire) . ") ";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;

    }

    public function delete()
    {
        if (!is_null($this->id)) {
            $query = "delete from ticket ";
            $query .= " where id=" . \core\Mysqli::real_escape_string_html($this->id);
            \core\Mysqli::query($query);
            $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
            \core\Mysqli::close();
            return $res;
        }
        return false;
    }

    public static function savTicket($classe, $fonction, $args, $expire = 0)
    {
        $data = array("classe" => $classe, "fonction" => $fonction, "args" => $args);
        $data = json_encode($data);
        $id = 0;
        do {
            $id = sha1(uniqid());
            $query = "select * from ticket ";
            $query .= " where id=" . \core\Mysqli::real_escape_string_html($id);
            \core\Mysqli::query($query);
            $u = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        } while ($u);
        $t = new Ticket();
        $t->id = $id;
        $t->donnee = $data;
        if ($expire > 0)
            $t->expire = time() + $expire;
        return ($t->insert() ? $id : false);
    }

    /*public static function savTicketExpire($classe, $fonction, $args)
    {
        $data = array("classe" => $classe, "fonction" => $fonction, "args" => $args);
        $data = json_encode($data);
        $id = 0;
        do {
            $id = sha1(uniqid());
            $query = "select * from ticket ";
            $query .= " where id=" . \core\Mysqli::real_escape_string_html($id);
            \core\Mysqli::query($query);
            $u = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        } while ($u);
        $t = new Ticket();
        $t->id = $id;
        $t->donnee = $data;
        return ($t->insert() ? $id : false);
    }*/

    public static function traiteTicket($id)
    {
        $query = "select id, donnee, UNIX_TIMESTAMP(expire) as expire from ticket ";
        $query .= " where id=" . \core\Mysqli::real_escape_string_html($id);
        \core\Mysqli::query($query);
        $ticket = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        if ($ticket != null)
            if ( $ticket->expire != null)
            if ($ticket->expire <= time()) {
                $ticket->delete();
                $ticket = null;
            }
        return $ticket;
    }
} 