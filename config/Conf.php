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
    static $nomdusite = "MediasTorrent";
    static $version = "#A.2.0";
    static $anneefondation = "2013";
    static $author = "Salorium";
    static $databases = array(
        "default" => array(
            "host" => "mysql",
            "database" => "mediastorrent",
            "login" => "mediastorrent",
            "password" => "azerty"
        )
    );
    static $memcachedserver = array(
        array("localhost",11211)
    );
    static $user = null;
    static $numerorole = array("Visiteur","Normal",array("Torrent","Uploadeur"),"Sysop");
    static $rolenumero; // Ne pas modifier
    static $rolevue;
    static $videoExtensions = array ('avi', 'asf', 'flv', 'mkv', 'mov', 'mp4', 'mpg', 'mpeg', 'ogm', 'rm', 'wmv','rar' );
    static $musicExtensions = array ('mp3', 'flac', 'ogg' );
    static $portscgi = 5001;
    static $te = "dd";
}

