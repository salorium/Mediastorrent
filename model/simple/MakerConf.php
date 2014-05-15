<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 05/05/14
 * Time: 04:25
 */

namespace model\simple;


use config\Conf;

class MakerConf extends \core\Model
{
    static function  makerConfSavBDD($host, $login, $password)
    {
        $content = '<?php
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
    static $install = true;
    static $nomdusite = "MediasTorrent";
    static $version = "#A.2.0";
    static $anneefondation = "2013";
    static $author = "Salorium";
    static $rootpassword = "qzwxecasd9";
    static $nomvg = "vg0";
    static $databases = array(
        "default" => array(
            "host" => ' . MakerConf::makeParam($host) . ',
            "database" => ' . MakerConf::makeParam("mediastorrent") . ',
            "login" => ' . MakerConf::makeParam($login) . ',
            "password" => ' . MakerConf::makeParam($password) . '
        )
    );
    static $memcachedserver = array(
        array("localhost", 11211)
    );
    static $user = array("user"=>null,"role"=>0,"roletxt"=>"Install");
    static $api_key_themoviedb = "57b59be276081344c6073b1989f4d57e";
    static $numerorole = array("Install","Visiteur", "Normal", "Torrent", "Sysop");
    static $rolenumero; // Ne pas modifier
    static $rolevue;
    static $videoExtensions = array("avi", "asf", "flv", "mkv", "mov", "mp4", "mpg", "mpeg", "ogm", "rm", "wmv", "rar");
    static $musicExtensions = array("mp3", "flac", "ogg");
    static $portscgi = 5001;
}

?>';

        file_put_contents(ROOT . DS . "config" . DS . "Conf.php", $content);

    }

    static function  makerConfSavBDDEnd($host, $login, $password)
    {
        $content = '<?php
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
    static $rootpassword = "qzwxecasd9";
    static $nomvg = "vg0";
    static $databases = array(
        "default" => array(
            "host" => ' . MakerConf::makeParam($host) . ',
            "database" => ' . MakerConf::makeParam("mediastorrent") . ',
            "login" => ' . MakerConf::makeParam($login) . ',
            "password" => ' . MakerConf::makeParam($password) . '
        )
    );
    static $memcachedserver = array(
        array("localhost", 11211)
    );
    static $user = array("user"=>null,"role"=>0,"roletxt"=>"Install");
    static $api_key_themoviedb = "57b59be276081344c6073b1989f4d57e";
    static $numerorole = array("Install","Visiteur", "Normal", "Torrent", "Sysop");
    static $rolenumero; // Ne pas modifier
    static $rolevue;
    static $videoExtensions = array("avi", "asf", "flv", "mkv", "mov", "mp4", "mpg", "mpeg", "ogm", "rm", "wmv", "rar");
    static $musicExtensions = array("mp3", "flac", "ogg");
    static $portscgi = 5001;
}

?>';

        file_put_contents(ROOT . DS . "config" . DS . "Conf.php", $content);

    }

    static function  makerConfEnd()
    {
        $content = '<?php
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
    static $rootpassword = "qzwxecasd9";
    static $nomvg = "vg0";
    static $databases = array(
        "default" => array(
            "host" => ' . MakerConf::makeParam(Conf::$databases["default"]["host"]) . ',
            "database" => ' . MakerConf::makeParam("mediastorrent") . ',
            "login" => ' . MakerConf::makeParam(Conf::$databases["default"]["login"]) . ',
            "password" => ' . MakerConf::makeParam(Conf::$databases["default"]["password"]) . '
        )
    );
    static $memcachedserver = array(
        array("localhost", 11211)
    );
    static $user = array("user"=>null,"role"=>0,"roletxt"=>"Install");
    static $api_key_themoviedb = "57b59be276081344c6073b1989f4d57e";
    static $numerorole = array("Install","Visiteur", "Normal", "Torrent", "Sysop");
    static $rolenumero; // Ne pas modifier
    static $rolevue;
    static $videoExtensions = array("avi", "asf", "flv", "mkv", "mov", "mp4", "mpg", "mpeg", "ogm", "rm", "wmv", "rar");
    static $musicExtensions = array("mp3", "flac", "ogg");
    static $portscgi = 5001;
}

?>';

        file_put_contents(ROOT . DS . "config" . DS . "Conf.php", $content);

    }

    static function makeParam($res)
    {
        if (is_bool($res))
            return ($res ? "true" : "false");
        if (is_string($res))
            return '"' . $res . '"';
    }
} 