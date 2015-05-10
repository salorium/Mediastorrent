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
$sortie = \model\simple\Console::execute("sed -i\".bak\" '/t10/d' /etc/fstab");
if ($sortie[0] !== 0) {
    \model\simple\Console::println("Impossible de démonter /dev/" . \config\Conf::$nomvg . '/' . $login);
    sleep(10);
}
/*
$login = "t2";
$sortie = \model\simple\MakerRtorrentLancer::stop($login);
if ($sortie[0] !== 0) {
    \model\simple\Console::println("Impossible d'arrêté rtorrent");
}
//Voir l'utilisateur utilise lvm
if (!is_null(\config\Conf::$nomvg)) {
    $sortie = \model\simple\Console::executePath("lvdisplay /dev/" . \config\Conf::$nomvg . '/' . $login);
    if ($sortie[0] === 0) {
        \model\simple\Console::println("Suppression du lvm en cour");
        //Demontage de l'home de l'utilisateur
        do {
            $sortie = \model\simple\Console::execute("umount -f /dev/" . \config\Conf::$nomvg . '/' . $login);
            if ($sortie[0] !== 0) {
                \model\simple\Console::println("Impossible de démonter /dev/" . \config\Conf::$nomvg . '/' . $login);
                sleep(10);
            }
        } while ($sortie[0] !== 0);

        $sortie = \model\simple\Console::executePath("lvremove -f /dev/" . \config\Conf::$nomvg . '/' . $login);
        if ($sortie[0] !== 0) {
            throw new \Exception("Impossible de supprimer /dev/" . \config\Conf::$nomvg . '/' . $login);
        }
    } else {
        \model\simple\Console::println("Pas de lvm");
    }
}
\model\simple\Console::println("Suppression de l'utilisateur");
$sortie = \model\simple\Console::executePath("userdel -r " . escapeshellarg($login));
if ($sortie[0] !== 0) {
    throw new \Exception("Impossible de supprimer l'utilisateur " . $login);
}
//\model\bash\Utilisateur::addRtorrent("salorium", 5001);
//exec("nano test.tester");
/*$taille = 100;
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
*/
?>