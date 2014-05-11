<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 11/05/14
 * Time: 06:43
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
    $login = $argv[1];
    //$password = $argv[2];
    $taille = $argv[2];
    $scgi = $argv[3];
    /*\model\simple\Console::println("Création de l'utilisateur sur le systeme");
    $a = \model\simple\Ssh::execute("root", \config\Conf::$rootpassword, 'useradd -m  -s /bin/bash ' . escapeshellarg($login));
    if ($a["error"] !== "") {
        throw new Exception("Erreur : " . $a["error"]);
    }
    //\model\simple\Console::println('echo "'.($login).':'.($password).'" | chpasswd');
    /*\model\simple\Console::println("Initiation du mot de passe de l'utilisateur");
    $a = \model\simple\Ssh::execute("root",\config\Conf::$rootpassword,'echo "'.$login.':'.str_replace('"','\"',escapeshellcmd($password)).'" | chpasswd');
    if ( $a["error"]!==""){
        throw new Exception("Erreur : ".$a["error"]);
    }*/
    /*\model\simple\Console::println("Teste si l'utilisateur peux avoir la quantité voulu =>" . $taille . "Go");
    $a = \model\simple\Ssh::execute("root", \config\Conf::$rootpassword, 'vgdisplay -c ' . config\Conf::$nomvg . ' | awk -F ":" \'{print $16}\'');
    if ($a["error"] !== "") {
        throw new Exception("Erreur : " . $a["error"]);
    }
    $extends = ((int)$a["sortie"]);
    $a = \model\simple\Ssh::execute("root", \config\Conf::$rootpassword, 'vgdisplay -c ' . config\Conf::$nomvg . ' | awk -F ":" \'{print $13}\'');
    if ($a["error"] !== "") {
        throw new Exception("Erreur : " . $a["error"]);
    }
    $tailleextends = ((int)$a["sortie"]);
    $free = ($extends * $tailleextends / 1024 / 1024);
    \model\simple\Console::println("Quantité libre " . $free . "Go");
    if ($taille > $free) {
        $taille = $free;
    }
    \model\simple\Console::println("Quantité de l'espace " . $taille . "Go");
    \model\simple\Console::println("Création du volume de taille =>" . $taille . "Go");
    $a = \model\simple\Ssh::execute("root", \config\Conf::$rootpassword, 'lvcreate -n ' . $login . ' -L ' . $taille . 'g ' . config\Conf::$nomvg);
    if ($a["error"] !== "") {
        throw new Exception("Erreur : " . $a["error"]);
    }
    \model\simple\Console::println("Formatage du volume en Ext4");
    $a = \model\simple\Ssh::execute("root", \config\Conf::$rootpassword, 'mkfs -t ext4 /dev/' . config\Conf::$nomvg . '/' . $login);
    if ($a["error"] !== "mke2fs 1.42.5 (29-Jul-2012)\n\n") {
        throw new Exception("Erreur : " . $a["error"]);
    }
    \model\simple\Console::println("Montage de /dev/" . config\Conf::$nomvg . "/" . $login . " dans /home/" . $login);
    $a = \model\simple\Ssh::execute("root", \config\Conf::$rootpassword, 'mount /dev/' . config\Conf::$nomvg . '/' . $login . " /home/" . $login);
    if ($a["error"] !== "") {
        throw new Exception("Erreur : " . $a["error"]);
    }
    \model\simple\MakerRtorrentConf::create($login, $scgi);
    \model\simple\Console::println("Creation du fichier config rtorrent dans /home/" . $login . "/.rtorrent.rc");
    $a = \model\simple\Ssh::execute("root", \config\Conf::$rootpassword, 'mv ' . ROOT . DS . "cache" . DS . $login . "rtorrent" . " /home/" . $login . "/.rtorrent.rc");
    if ($a["error"] !== "") {
        throw new Exception("Erreur : " . $a["error"]);
    }
    \model\simple\Console::println('Creation /home/' . $login . '/rtorrent/data');
    $a = \model\simple\Ssh::execute("root", \config\Conf::$rootpassword, 'mkdir -p /home/' . $login . '/rtorrent/data');
    if ($a["error"] !== "") {
        throw new Exception("Erreur : " . $a["error"]);
    }
    \model\simple\Console::println('Creation /home/' . $login . '/rtorrent/session');
    $a = \model\simple\Ssh::execute("root", \config\Conf::$rootpassword, 'mkdir -p /home/' . $login . '/rtorrent/session');
    if ($a["error"] !== "") {
        throw new Exception("Erreur : " . $a["error"]);
    }
    \model\simple\Console::println('Attribution du propriétaire ' . $login . ' /home/' . $login . '');
    $a = \model\simple\Ssh::execute("root", \config\Conf::$rootpassword, 'chown -R ' . $login . ':' . $login . ' /home/' . $login);
    if ($a["error"] !== "") {
        throw new Exception("Erreur : " . $a["error"]);
    }
    \model\simple\Console::println('Ajout de l\'auto start');
    $a = \model\simple\Ssh::execute("root", \config\Conf::$rootpassword, 'echo "mount /dev/' . config\Conf::$nomvg . '/' . $login . " /home/" . $login . '" >> /etc/init.d/rtorrentall');
    if ($a["error"] !== "") {
        throw new Exception("Erreur : " . $a["error"]);
    }
    $a = \model\simple\Ssh::execute("root", \config\Conf::$rootpassword, 'echo "/etc/init.d/rtorrent \$1 ' . $login . ' "' . $scgi . ' >> /etc/init.d/rtorrentall');
    if ($a["error"] !== "") {
        throw new Exception("Erreur : " . $a["error"]);
    }*/
    \model\simple\Console::println('Lancement de rtorrent');
    $a = \model\simple\Ssh::execute1("root", \config\Conf::$rootpassword, '/etc/init.d/rtorrent start ' . $login . ' ' . $scgi);
    if ($a["error"] !== "") {
        throw new Exception("Erreur : " . $a["error"]);
    }
} else {
    \model\simple\Console::println(basename(__FILE__) . " <hostmysql> <loginmysql> <passmysql>");
}
?>
