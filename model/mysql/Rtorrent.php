<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 26/10/13
 * Time: 07:33
 * To change this template use File | Settings | File Templates.
 */

namespace model\mysql;


class Rtorrent extends \core\ModelMysql
{
    public $hostname;
    public $nom;

    public function insert()
    {
        if (is_null($this->hostname) || is_null($this->nom))
            return false;
        $query = "insert into rtorrent (hostname,nom) values(";
        $query .= \core\Mysqli::real_escape_string_html($this->hostname) . ",";
        $query .= \core\Mysqli::real_escape_string_html($this->nom) . ")";
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;

    }

    public static function addRtorrentServeur($nom)
    {
        $rtorrent = new Rtorrent();
        $rtorrent->nom = $nom;
        $rtorrent->hostname = HOST;
        return $rtorrent->insert();
    }

    public static function addRtorrentServeur1($nom, $host)
    {
        $rtorrent = new Rtorrent();
        $rtorrent->nom = $nom;
        $rtorrent->hostname = $host;
        return $rtorrent->insert();
    }

    public static function getRtorrentsDeUtilisateur($login)
    {
        $query = "select nom, portscgi, hostname from rtorrent, rtorrents ";
        $query .= "where login=" . \core\Mysqli::real_escape_string_html($login) . " and nom=nomrtorrent";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
    }

    public static function getUserscgiDeUtilisateur($login)
    {
        $query = "select login as userscgi from rtorrent, rtorrents ";
        $query .= "where login=" . \core\Mysqli::real_escape_string_html($login) . " and nom=nomrtorrent and hostname=" . \core\Mysqli::real_escape_string_html(HOST);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
    }

    public static function retirerServeur()
    {
        $query = "delete from rtorrent where hostname=" . \core\Mysqli::real_escape_string_html(HOST);
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;
    }

    public static function getHostRtorrent()
    {
        $query = "select hostname from rtorrent";
        $query .= " where nom=" . \core\Mysqli::real_escape_string_html(\config\Conf::$nomrtorrent);
        \core\Mysqli::query($query);
        $objet = \core\Mysqli::getObjectAndClose();
        return $objet->hostname;
    }

    public static function isRtorrentServeur()
    {
        $query = "select count(*) as nb from rtorrent";
        $query .= " where hostname=" . \core\Mysqli::real_escape_string_html(HOST);
        \core\Mysqli::query($query);
        $objet = \core\Mysqli::getObjectAndClose();
        return ($objet->nb == 1);
        //die();
    }

}