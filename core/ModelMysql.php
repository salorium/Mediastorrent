<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 27/10/13
 * Time: 08:32
 * To change this template use File | Settings | File Templates.
 */

namespace core;


class ModelMysql extends Model
{
    //public $table=false;
    function __construct()
    {
        /* if ( $this->table === false){
             $ta = explode("\\",get_class($this));
             $this->table = strtolower($ta[count($ta)-1]);
         }
 */
    }

    public function find($req)
    {
        $table = array();
        $compteur = 1;
        $wherestring = "";
        $selectstring = "";
        $fromstring = "";
        $table[$this->table] = $this->table;
        if (isset($req["where"])) {
            $where = $req["where"];
            if (is_array($where)) {
                foreach ($where as $v) {
                    if ($v instanceof SqlWhere) {
                        $wherestring .= $v->getString($table);
                    } else {
                        throw new \Exception("ERREUR SQLWHERE");
                    }
                }
            } else if ($where instanceof SqlWhere) {
                $wherestring .= $where->getString($table);
            } else {
                throw new \Exception("ERREUR SQLWHERE");
            }
        }
        var_dump($table);
        if (isset($req["select"])) {
            $select = $req["select"];
            if (is_array($select)) {
                foreach ($select as $v) {
                    if ($v instanceof SqlSelect) {

                        $selectstring .= $v->getString($table);
                    } elseif (is_string($v)) {
                        $selectstring .= $v . ", ";
                    } else {
                        throw new \Exception("ERREUR SQLSELECT");
                    }
                }
            } elseif ($select instanceof SqlSelect) {
                $selectstring .= $v->getString($table);
            } elseif (is_string($select)) {
                $selectstring .= $select . ", ";
            } else {
                throw new \Exception("ERREUR SQLSELECT");
            }
            $selectstring = substr($selectstring, 0, -2);
        }

        foreach ($table as $k => $v) {
            $fromstring .= $v . ", ";
        }
        $fromstring = substr($fromstring, 0, -2);
        $requete = "SELECT " . $selectstring . " FROM " . $fromstring . " WHERE " . $wherestring;
        var_dump($requete);

        die();


    }
}