<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 11/05/14
 * Time: 15:36
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
if ($argc == 3) {
    $login = $argv[1];
    $scgi = $argv[2];
    exec("/etc/init.d/rtorrent start " . $login . " " . $scgi);
} else {
    \model\simple\Console::println(basename(__FILE__) . " <login> <scgi>");
}
?>
