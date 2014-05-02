<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 01/05/14
 * Time: 21:14
 */

namespace model\mysql;


class Genre extends \core\Model
{
    public $id;
    public $label;

    public function insert()
    {
        if (is_null($this->id) || is_null($this->label))
            return false;
        $query = "insert into genre (id,label) values(";
        $query .= \core\Mysqli::real_escape_string($this->id) . ",";
        $query .= \core\Mysqli::real_escape_string($this->label) . ")";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;
    }
} 