<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 26/04/14
 * Time: 20:35
 */

namespace model\simple;


class MakerCache
{
    static function maker($filename, $option)
    {
        $contenu = '<?php
namespace config;
class Conf
{
    static $install = false;
    static $debug = true;
    static $nomdusite = "MediasTorrent";
    static $version = "#A.2.0";
    static $anneefondation = "2013";
    static $author = "Salorium";
    static $databases = array(
        "default" => array(
            "host" => "mysql.salorium.com",
            "database" => "mediastorrent",
            "login" => "mediastorrent",
            "password" => "azerty"
        )
    );
    static $memcachedserver = array(
        array("localhost",11211)
    );
    static $user = null;
    static $api_key_themoviedb = "57b59be276081344c6073b1989f4d57e";
    static $numerorole = array("Visiteur","Normal",array("Torrent","Uploadeur"),"Sysop");
    static $rolenumero; // Ne pas modifier
    static $rolevue;
    static $videoExtensions = array ("avi", "asf", "flv", "mkv", "mov", "mp4", "mpg", "mpeg", "ogm", "rm", "wmv","rar" );
    static $musicExtensions = array ("mp3", "flac", "ogg" );
    static $portscgi = 5001;
}
?>';
    }
} 