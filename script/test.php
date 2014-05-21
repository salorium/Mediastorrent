<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 30/04/14
 * Time: 00:37
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

\config\Conf::$debuglocalfile = false;
//\model\bash\Utilisateur::addRtorrent("salorium", 5001);
//exec("nano test.tester");
$taille = 100;
$sortie = \model\simple\Console::execute('vgdisplay -c ' . \config\Conf::$nomvg . ' | awk -F ":" \'{print $16}\'');
if ($sortie[0] === 1) {
    throw new \Exception("Lvm ou le volume groupe " . \config\Conf::$nomvg . " est il bien disponible ?");
}
$extends = ((int)$sortie[1]);
$sortie = \model\simple\Console::execute('vgdisplay -c ' . \config\Conf::$nomvg . ' | awk -F ":" \'{print $13}\'');
if ($sortie[0] === 1) {
    throw new \Exception("Lvm ou le volume groupe " . \config\Conf::$nomvg . " est il bien disponible ?");
}
$tailleextends = ((int)$sortie[1]);
$free = (int)($extends * $tailleextends / 1024 / 1024);
\model\simple\Console::println("Quantité libre " . $free . "Go");
if ($taille > $free) {
    $taille = $free;
}

?>