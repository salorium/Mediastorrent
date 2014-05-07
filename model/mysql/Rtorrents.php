<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 26/10/13
 * Time: 07:31
 * To change this template use File | Settings | File Templates.
 */

namespace model\mysql;


class Rtorrents
{

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
}