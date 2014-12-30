<?php
/**
 * Created by PhpStorm.
 * User: AzuriS
 * Date: 09/10/2014
 * Time: 23:00
 */
namespace model\mysql;

use core\Mysqli;

class Torrents extends \core\ModelMysql
{

    public static function countAllTorrents()
    {
        $query = "SELECT COUNT(id) FROM torrents";

    }

    public static function getAllTorrents($depart = 0)
    {
        $query = "select SQL_CALC_FOUND_ROWS * from torrents limit " . $depart . "," . \config\Conf::$nbtorrentparpage;
        \core\Mysqli::query($query);
        $data = \core\Mysqli::getObject(true, __CLASS__);
        $query = "SELECT FOUND_ROWS() as nb;";
        \core\Mysqli::query($query);
        $nb = \core\Mysqli::getObjectAndClose(false, __CLASS__);
        return array("total" => $nb->nb, "data" => $data);
    }

    public static function insertTorrent($hashinfo, $data)
    {
        $query = "Insert into torrents values( NULL," . \core\Mysqli::real_escape_string($hashinfo) . ", 0,0,'0000-00-00 00:00:00','0',0,0)";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        if ($res) {
            $id = \core\Mysqli::getLastId();
            $query = "Insert into torrents_files values( " . $id . "," . \core\Mysqli::real_escape_string($data) . ")";
            \core\Mysqli::query($query);
            $res &= (\core\Mysqli::nombreDeLigneAffecte() == 1);
        }
        \core\Mysqli::close();
        return ($res ? $id : $res);
    }

    public static function getDescription($id)
    {
        $query = "SELECT * FROM torrents WHERE id = " . \core\Mysqli::real_escape_string($id);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true, __CLASS__);
    }

}