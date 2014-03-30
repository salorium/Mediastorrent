<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 26/10/13
 * Time: 07:33
 * To change this template use File | Settings | File Templates.
 */

namespace model\mysql;


class Rtorrent  extends \core\Model {
    public $hostname;
    public $nom;
    public function test(){
        echo "Rtorrent";
    }
    public static function getRtorrentsDeUtilisateur($login){
        $query = "select nom, portscgi, hostname from rtorrent, rtorrents ";
        $query .="where login='".\core\Mysqli::real_escape_string($login)."' and nom=nomrtorrent";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
    }

}