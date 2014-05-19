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
\model\simple\Console::println("Configuration de mediastorrent");
exec("chmod a+w " . ROOT . DS . "log");
exec("chmod a+w " . ROOT . DS . "cache");
exec("chmod a+w " . ROOT . DS . "config" . DS . "Conf.php");
exec('echo "php ' . ROOT . DS . "script" . DS . 'cronroot.php >> ' . ROOT . DS . "log" . DS . 'cronroot.log"  >> ' . ROOT . DS . "script" . DS . "cronroot.sh");
exec("chmod a+x " . ROOT . DS . "script" . DS . "cronroot.sh");
\model\simple\Console::println("Configuration de mysql");
$host = \model\simple\Console::saisieString("Entré host de mysql");
$login = \model\simple\Console::saisieString("Entré le login de mysql");
$mdp = \model\simple\Console::saisieString("Entré le mot de passe de mysql");
$querys = file_get_contents(ROOT . DS . "mysql" . DS . "mediastorrent.sql");
\core\Mysqli::initmultiquery($host, $login, $mdp, $querys);
$lvm = \model\simple\Console::saisieBoolean("Est ce que vous utiliserez lvm2 ?");
$volumegroup = "";
if ($lvm) {
    $volumegroup = \model\simple\Console::saisieString("Entré le nom du volume group que vous utiliserez");
    $sortie = \model\simple\Console::execute('vgdisplay -c ' . $volumegroup);
    if ($sortie[0] === 1) {
        $lvm = false;
        \model\simple\Console::println($volumegroup . " non disponible => désactivation du support de lvm2");
    }
}
\model\simple\MakerConf::make($host, $login, $mdp, $lvm, $volumegroup);
exec("crontab -l > mycron");
exec('echo "*/1 * * * * ' . ROOT . DS . "script" . DS . 'cronroot.sh"  >> mycron');
exec("crontab mycron");
exec("rm mycron");
\model\simple\Console::println("Fini");
?>