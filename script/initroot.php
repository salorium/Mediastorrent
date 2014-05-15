<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 15/05/14
 * Time: 16:05
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
\model\simple\Console::println("Initialisation du cron du root");
exec("chmod a+w " . ROOT . DS . "log");
exec("chmod a+w " . ROOT . DS . "cache");
exec("chmod a+w " . ROOT . DS . "config" . DS . "Conf.php");
exec("crontab -l > mycron");
exec('echo "*/1 * * * *  >> mycron');
exec("crontab mycron");
exec("rm mycron");
\model\simple\Console::println("Fini");
?>