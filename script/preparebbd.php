<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 09/05/14
 * Time: 01:13
 */
define('WEBROOT', __DIR__);
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);

function __autoload($class_name)
{
    $filename = ROOT . DS . str_replace("\\", DS, $class_name) . ".php";
    if (file_exists($filename)) {
        require_once $filename;
    } else {

    }

}

//Retour visuel
\config\Conf::$debuglocalfile = false;
if ($argc == 4) {
    $host = $argv[1];
    $login = $argv[2];
    $pass = $argv[3];
    $querys = file_get_contents(ROOT . DS . "mysql" . DS . "mediastorrent.sql");
    \core\Mysqli::initmultiquery($host, $login, $pass, $querys);
    \model\simple\MakerConf::makerConfSavBDD($host, $login, $pass);
    \model\simple\MakerConf::makerConfEnd();
} else {
    \model\simple\Console::println(basename(__FILE__) . " <hostmysql> <loginmysql> <passmysql>");
}
