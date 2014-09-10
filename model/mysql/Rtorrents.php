<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 26/10/13
 * Time: 07:31
 * To change this template use File | Settings | File Templates.
 */

namespace model\mysql;


class Rtorrents extends \core\ModelMysql
{
    public $nomrtorrent;
    public $login;
    public $portscgi;

    public function insert()
    {
        if (is_null($this->nomrtorrent) || is_null($this->login) || is_null($this->portscgi))
            return false;
        $query = "insert into rtorrents (nomrtorrent,login,portscgi) values(";
        $query .= \core\Mysqli::real_escape_string($this->nomrtorrent) . ",";
        $query .= \core\Mysqli::real_escape_string($this->login) . ",";
        $query .= \core\Mysqli::real_escape_string($this->portscgi) . ")";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;

    }

    static function getRtorrentsDispoPourUtilisateur($login)
    {
        $query = "select r.hostname as hostname, r.nom as nomrtorrent, rs.portscgi as portscgi ";
        $query .= "from rtorrent r ";
        $query .= "LEFT JOIN rtorrents rs ";
        $query .= "ON rs.nomrtorrent = r.nom ";
        $query .= "where r.nom not in ( select nomrtorrent from rtorrents where login =" . \core\Mysqli::real_escape_string($login) . ")";
        \core\Mysqli::query($query);
        $rtorrent = \core\Mysqli::getObjectAndClose(true);
        $rtable = array();
        if (!is_bool($rtorrent))
            foreach ($rtorrent as $v) {
                if (isset($rtable[$v->nomrtorrent . ""])) {
                    if (!is_null($v->portscgi)) {
                        $rtable[$v->nomrtorrent . ""]["scgi"][] = $v->portscgi;
                    }
                } else {
                    $rtable[$v->nomrtorrent . ""] = array("host" => $v->hostname, "scgi" => array());
                    if (!is_null($v->portscgi)) {
                        $rtable[$v->nomrtorrent . ""]["scgi"][] = $v->portscgi;
                    }
                }
            }
        return $rtable;
    }

    static function getAllRtorrentUtilisateur($login)
    {
        $query = "select nomrtorrent ";
        $query .= "from rtorrents ";
        $query .= "where ";
        $query .= "login =" . \core\Mysqli::real_escape_string($login);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
    }

    static function addRtorrentUtilisateurScgi($login, $nomrtorrent, $scgi)
    {
        $rtorrents = new Rtorrents();
        $rtorrents->login = $login;
        $rtorrents->nomrtorrent = $nomrtorrent;
        $rtorrents->portscgi = $scgi;
        return $rtorrents->insert();
    }
}