<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 27/10/13
 * Time: 08:36
 * To change this template use File | Settings | File Templates.
 */

namespace config;


class Conf
{
    static $debug = true;
    static $debuglocal = true;
    static $debuglocalfile = true;
    static $install = false;
    static $nomdusite = "MediasTorrent";
    static $version = "#A.2.0";
    static $anneefondation = "2013";
    static $author = "Salorium";
    static $databases = array(
        "default" => array(
            "host" => "mysql.salorium.com",
            "database" => "mediastorrent",
            "login" => "mediastorrent",
            "password" => "aqwzsx"
        )
    );
    static $memcachedserver = array(
        array("localhost", 11211)
    );
    static $user = array("user" => null, "role" => 0, "roletxt" => "Install");
    static $api_key_themoviedb = "57b59be276081344c6073b1989f4d57e";
    static $numerorole = array("Install", "Visiteur", "Normal", array("Torrent", "Uploadeur"), "Sysop");
    static $rolenumero; // Ne pas modifier
    static $rolevue;
    static $videoExtensions = array("avi", "asf", "flv", "mkv", "mov", "mp4", "mpg", "mpeg", "ogm", "rm", "wmv", "rar");
    static $musicExtensions = array("mp3", "flac", "ogg");
    static $portscgi = 5001;
}

?>
