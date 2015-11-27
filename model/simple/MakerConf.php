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
    static function maker()
    {
        $user = \config\Conf::$user;
        $rolenumero = \config\Conf::$rolenumero;
        $rolevue = \config\Conf::$rolevue;
        $userscgi = \config\Conf::$userscgi;
        $debuglocalfile = \config\Conf::$debuglocalfile;
        \config\Conf::$debuglocalfile = true;
        \config\Conf::$user['user']= null;
        \config\Conf::$user['role']=0;
        \config\Conf::$user['roletxt']="Install";
        \config\Conf::$rolenumero = null;
        \config\Conf::$rolevue = null;
        \config\Conf::$userscgi = null;

        $res = '<?php
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
';
        $t =  get_class_vars("\config\Conf");
        foreach ($t as $k=>$v){
            $res.='     static $'.$k." = ". var_export($v,true).";\n";
        }

$res.='}';
        \config\Conf::$user = $user;
        \config\Conf::$rolenumero =$rolenumero;
        \config\Conf::$rolevue = $rolevue ;
        \config\Conf::$userscgi = $userscgi ;
        \config\Conf::$debuglocalfile = $debuglocalfile;
        return $res;

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

    static function  makerConfEnd()
    {
        \config\Conf::$install = false;
        $content = MakerConf::maker();
        file_put_contents(ROOT . DS . "config" . DS . "Conf.php", $content);

    }
} 