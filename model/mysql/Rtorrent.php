<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 26/10/13
 * Time: 07:33
 * To change this template use File | Settings | File Templates.
 */

namespace model\mysql;


class Rtorrent extends \core\Model
{
    public $hostname;
    public $nom;

    public function insert()
    {
        if (is_null($this->hostname) || is_null($this->nom))
            return false;
        $query = "insert into rtorrent (hostname,nom) values(";
        $query .= \core\Mysqli::real_escape_string($this->hostname) . ",";
        $query .= \core\Mysqli::real_escape_string($this->nom) . ")";
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

    public static function getRtorrentsDeUtilisateur($login)
    {
        $query = "select nom, portscgi, hostname from rtorrent, rtorrents ";
        $query .= "where login=" . \core\Mysqli::real_escape_string($login) . " and nom=nomrtorrent";
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
    }

    public static function getPortscgiDeUtilisateur($login)
    {
        $query = "select portscgi from rtorrent, rtorrents ";
        $query .= "where login=" . \core\Mysqli::real_escape_string($login) . " and nom=nomrtorrent and hostname=" . \core\Mysqli::real_escape_string(HOST);
        \core\Mysqli::query($query);
        return \core\Mysqli::getObjectAndClose(true);
    }

    public static function retirerServeur()
    {
        $query = "delete from rtorrent where hostname=" . \core\Mysqli::real_escape_string(HOST);
        \core\Mysqli::query($query);
        $res = (\core\Mysqli::nombreDeLigneAffecte() == 1);
        \core\Mysqli::close();
        return $res;
    }

    public static function isRtorrentServeur()
    {
        $query = "select count(*) as nb from rtorrent";
        $query .= " where hostname=" . \core\Mysqli::real_escape_string(HOST);
        \core\Mysqli::query($query);
        $objet = \core\Mysqli::getObjectAndClose();
        return ($objet->nb == 1);
        //die();
    }

}