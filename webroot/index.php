<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 25/10/13
 * Time: 11:02
 * To change this template use File | Settings | File Templates.
 */
header("Access-Control-Allow-Origin: *");
define('WEBROOT', __DIR__);
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);
define('CORE', ROOT . DS . 'core');
define('BASE_URL', "http" . ($_SERVER["SERVER_PORT"] == 80 ? "" : "s") . "://" . $_SERVER["HTTP_HOST"] . dirname(dirname($_SERVER["SCRIPT_NAME"])) . ($_SERVER["SCRIPT_NAME"] !== "/index.php" ? "/" : ""));
define('HOST', substr($_SERVER["HTTP_HOST"] . dirname(dirname($_SERVER["SCRIPT_NAME"])) . ($_SERVER["SCRIPT_NAME"] !== "/index.php" ? "/" : ""), 0, -1));
function __autoload($class_name)
{
    $filename = ROOT . DS . str_replace("\\", DS, $class_name) . ".php";
    if (file_exists($filename)) {
        require_once $filename;
    } else {
        global $Dispa;
        trigger_error($class_name . " n'existe pas !");
        $Dispa->error($class_name . " n'existe pas !");
    }

}

/*

spl_autoload_register(function($className) {
    $fileName = stream_resolve_include_path(ROOT.DS.str_replace("\\",DS,$className).".php");
    if ($fileName !== false) {
        include $fileName;
    }else{
        global $Dispa;
        trigger_error($className." n'existe pas !");
        $Dispa->error($className." n'existe pas !");
    }
});
spl_autoload_register(function($traitName) {
    $fileName = stream_resolve_include_path(ROOT.DS.str_replace("\\",DS,$traitName).".php");
    if ($fileName !== false) {
        include $fileName;
    }
});

*/

function debug($var)
{
    $backtrace = debug_backtrace();
    echo '<a href="#"><strong>' . $backtrace[0]["file"] . '</strong> l.' . $backtrace[0]["line"];
    echo '<div class="panel"><pre>';
    print_r($var);
    echo "</pre></div></a>";
}

if (\config\Conf::$install) {
    core\Router::connect("Install", "/", "install/mysqlinit");
} else {
    //*
    core\Router::connect("Visiteur", "/", "utilisateur/index");
//
}
core\Router::connect("Normal", "/", "mediastorrent/accueil");
core\Router::connect("Torrent", "/", "mediastorrent/accueil");
core\Router::connect("Sysop", "/", "mediastorrent/accueil");
var_dump(\get_browser($_SERVER['HTTP_USER_AGENT'], true));
die();
$Dispa = new core\Dispatcher();

$Dispa->load();



?>