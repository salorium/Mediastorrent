<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 05/05/14
 * Time: 04:25
 */

namespace model\simple;


use config\Conf;
use core\Mysqli;

class MakerConf extends \core\Model
{
    /**
     * Todo: Voir si utilisé
     * @param $host
     * @param $login
     * @param $password
     */
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

    static function maker()
    {
        return '<?php
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
    static $debug = ' . var_export(\config\Conf::$debug, true) . ';
    static $debuglocal = ' . var_export(\config\Conf::$debuglocal, true) . ';
    static $debuglocalfile = ' . var_export(\config\Conf::$debuglocalfile, true) . ';
    static $install = ' . var_export(\config\Conf::$install, true) . ';
    static $nomdusite = ' . var_export(\config\Conf::$nomdusite, true) . ';
    static $version = ' . var_export(\config\Conf::$version, true) . ';
    static $anneefondation = ' . var_export(\config\Conf::$anneefondation, true) . ';
    static $author = ' . var_export(\config\Conf::$author, true) . ';
    static $nomrtorrent = ' . var_export(\config\Conf::$nomrtorrent, true) . ';
    static $distribution = ' . var_export(\config\Conf::$distribution, true) . ';
    static $init = ' . var_export(\config\Conf::$init, true) . ';
    static $nomvg = ' . var_export(\config\Conf::$nomvg, true) . ';
    static $databases = ' . var_export(\config\Conf::$databases, true) . ';
    static $memcachedserver = ' . var_export(\config\Conf::$memcachedserver, true) . ';
    static $clefdecryptage = ' . var_export(\config\Conf::$clefdecryptage, true) . ';
    static $user = array("user" => null, "role" => 0, "roletxt" => "Install");
    static $api_key_themoviedb = ' . var_export(\config\Conf::$api_key_themoviedb, true) . ';
    static $numerorole = ' . var_export(\config\Conf::$numerorole, true) . ';
    static $rolenumero; // Ne pas modifier
    static $rolevue;
    static $videoExtensions = ' . var_export(\config\Conf::$videoExtensions, true) . ';
    static $musicExtensions = ' . var_export(\config\Conf::$musicExtensions, true) . ';
    static $userscgi = ' . var_export(\config\Conf::$userscgi, true) . ';
}';
    }


    static function  make($host, $login, $password, $vgok, $vgname)
    {
        \config\Conf::$databases["default"]["host"] = $host;
        \config\Conf::$databases["default"]["database"] = "mediastorrent";
        \config\Conf::$databases["default"]["login"] = $login;
        \config\Conf::$databases["default"]["password"] = $password;
        $u = \model\mysql\Utilisateur::getAllUtilisateur();
        \config\Conf::$install = (count($u) === 0 ? true : false);
        \config\Conf::$nomvg = ($vgok ? $vgname : NULL);
        $content = MakerConf::maker();
        file_put_contents(ROOT . DS . "config" . DS . "Conf.php", $content);

    }

    static function  makeRtorrent($nomrtorrent)
    {
        \config\Conf::$nomrtorrent = $nomrtorrent;
        $content = MakerConf::maker();
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
        \config\Conf::$install = false;
        $content = MakerConf::maker();
        file_put_contents(ROOT . DS . "config" . DS . "Conf.php", $content);

    }

    static function makeParam($res)
    {
        if (is_array($res)) {
            $res1 = "array(";
            foreach ($res as $k => $v) {
                if (is_int($k)) {
                    $res1 .= MakerConf::makeParam($v) . ',';
                } else {
                    $res1 .= '"' . $k . '"=>' . MakerConf::makeParam($v) . ',';
                }

            }
            $res1 = substr($res1, 0, -1);
            $res1 .= ")";
            return $res1;
        }
        if (is_null($res))
            return "null";
        if (is_bool($res))
            return ($res ? "true" : "false");
        if (is_string($res))
            return '"' . $res . '"';
        if (is_numeric($res))
            return $res;
    }
} 