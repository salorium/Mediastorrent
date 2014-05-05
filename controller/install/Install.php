<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 26/04/14
 * Time: 13:47
 */

namespace controller\install;


class Install extends \core\Controller
{
    function index()
    {
        $this->layout = "install";
        //$memachedload = extension_loaded("memcached");
        $this->set(array(
            //"curl"=>extension_loaded("curl"),
            "memcached" => extension_loaded("memcached"),
            "mysqli" => extension_loaded("mysqli"),
            "imagick" => extension_loaded("imagick"),
            "json" => extension_loaded("json"),
            "curl" => extension_loaded("curl"),
            "ecrituredossiercache" => is_writable(ROOT . DS . "cache"),
            "ecriturefileconfig" => is_writable(ROOT . DS . "config" . DS . "Conf.php")
        ));
    }

    function mysqlinit()
    {
        $this->layout = "install";
        if (isset($_REQUEST["hostmysql"]) && isset($_REQUEST["loginmysql"]) && isset($_REQUEST["passmysql"])) {
            $querys = file_get_contents(ROOT . DS . "mysql" . DS . "mediastorrent.sql");
            \core\Mysqli::initmultiquery($_REQUEST["hostmysql"], $_REQUEST["loginmysql"], $_REQUEST["passmysql"], $querys);
            \model\simple\MakerConf::makerConfSavBDD($_REQUEST["hostmysql"], $_REQUEST["loginmysql"], $_REQUEST["passmysql"]);
            $this->set("res", true);
        } else if (isset($_REQUEST["login"]) && isset($_REQUEST["pass"]) && isset($_REQUEST["mail"])) {
            \model\mysql\Utilisateur::insertUtilisateurSysop($_REQUEST["login"], $_REQUEST["pass"], $_REQUEST["mail"]);
            \model\simple\MakerConf::makerConfEnd();
        } else {

        }
    }

    function enableModule($pass = null, $action = null)
    {
        set_time_limit(60);
        if (!is_null($pass) && !is_null($action)) {
            $_REQUEST["password"] = $pass;
            $_REQUEST["action"] = $action;
        }
        if (isset($_REQUEST["password"]) && isset($_REQUEST["action"])) {
            $tmp = null;
            switch ($_REQUEST["action"]) {
                case "mysqli":
                    $ti = \model\simple\Ssh::execute("root", $_REQUEST["password"], "apt-get -y install php5-mysqlnd");
                    $t[] = $ti;
                    if ($ti["error"] !== "") {
                        $ti = \model\simple\Ssh::execute("root", $_REQUEST["password"], "apt-get -y install php5-mysql");
                        $t[] = $ti;
                        $ti = \model\simple\Ssh::execute("root", $_REQUEST["password"], "dpkg --configure -a");
                        $t[] = $ti;

                    }
                    $t[] = \model\simple\Ssh::execute("root", $_REQUEST["password"], "service apache2 reload");
                    break;
                default:
                    $t[] = \model\simple\Ssh::execute("root", $_REQUEST["password"], "apt-get -y install php5-" . $_REQUEST["action"]);
                    $ti = \model\simple\Ssh::execute("root", $_REQUEST["password"], "dpkg --configure -a");
                    $t[] = $ti;
                    $t[] = \model\simple\Ssh::execute("root", $_REQUEST["password"], "service apache2 reload");
                    break;
            }
            $this->set(array(
                "result" => $t,
                "tmp" => $tmp
            ));
        }

    }

    function enableWriteFile($pass = null, $file = null)
    {
        if (!is_null($pass) && !is_null($file)) {
            $_REQUEST["password"] = $pass;
            $_REQUEST["file"] = $file;
        }
        if (isset($_REQUEST["password"]) && isset($_REQUEST["file"])) {
            $t = null;
            $write = false;
            switch ($_REQUEST["file"]) {
                case "cache":
                    $ti = \model\simple\Ssh::execute("root", $_REQUEST["password"], "chmod -R a+w " . ROOT . DS . "cache");
                    $t[] = $ti;
                    $write = is_writable(ROOT . DS . "cache");
                    break;

            }

            $this->set(array(
                "result" => $t,
                "ecriture" => $write
            ));
        }
    }

    function install($pass = null, $action = null)
    {
        set_time_limit(0);
        if (!is_null($pass) && !is_null($action)) {
            $_REQUEST["password"] = $pass;
            $_REQUEST["action"] = $action;
        }
        if (isset($_REQUEST["password"]) && isset($_REQUEST["action"])) {
            $tmp = null;
            switch ($_REQUEST["action"]) {
                case "memcached":
                    $ti = \model\simple\Ssh::execute("root", $_REQUEST["password"], "apt-get -y install memcached");
                    $t[] = $ti;
                    if ($ti["error"] !== "") {
                        $ti = \model\simple\Ssh::execute("root", $_REQUEST["password"], "apt-get -y install php5-mysql");
                        $t[] = $ti;
                        $ti = \model\simple\Ssh::execute("root", $_REQUEST["password"], "dpkg --configure -a");
                        $t[] = $ti;

                    }
                    $t[] = \model\simple\Ssh::execute("root", $_REQUEST["password"], "service apache2 reload");
                    break;
                default:
                    $t[] = \model\simple\Ssh::execute("root", $_REQUEST["password"], "apt-get -y install php5-" . $_REQUEST["action"]);
                    $ti = \model\simple\Ssh::execute("root", $_REQUEST["password"], "dpkg --configure -a");
                    $t[] = $ti;
                    $t[] = \model\simple\Ssh::execute("root", $_REQUEST["password"], "service apache2 reload");
                    break;
            }
            $this->set(array(
                "result" => $t,
                "tmp" => $tmp
            ));
        }
    }

    function checkModule($module)
    {
        $this->set(array(
            "extension" => extension_loaded($module)
        ));
    }

    function restartApache($pass = null)
    {
        if (!is_null($pass)) {
            $t[] = \model\simple\Ssh::execute("root", $pass, "service apache2 reload");
            $this->set(array(
                "result" => $t
            ));
        }
    }
} 