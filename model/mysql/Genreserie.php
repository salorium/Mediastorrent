<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 01/05/14
 * Time: 21:14
 */

namespace model\mysql;


class Genreserie extends \core\ModelMysql
{
    public $id;
    public $label;

    public function insert()
    {
        if (is_null($this->id) || is_null($this->label))
            return false;
        $query = "insert into genreserie (id,label) values(";
        $query .= \core\Mysqli::real_escape_string_html($this->id) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->label) . ")";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;
    }

    public static function getAllGenre()
    {
        $query = "select distinct g.label as label ";
        $query .= "from torrentserie tf, serie f, genreserie g ";
        $query .= "where ( ";
        $query .= "tf.idserie = f.id ";
        $query .= "and g.id = f.id ";
        $query .= "and tf.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login);
        //$query .= " and rs.nomrtorrent = r.nom ";
        $query .= " ) or ( ";
        //$query .= "tf.fini = true ";
        $query .= "tf.partageamis = true ";
        $query .= "and tf.idserie = f.id ";
        $query .= "and g.id = f.id ";
        //$query .= "and rs.nomrtorrent = r.nom ";
        $query .= "and tf.login in (select login from amis a1 where a1.demandeur = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a1.ok = true union select demandeur from amis a2 where a2.login = " . \core\Mysqli::real_escape_string_html(\config\Conf::$user["user"]->login) . " and a2.ok = true)";
        $query .= ") ORDER BY label ASC";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
    }
} 