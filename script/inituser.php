<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 09/05/14
 * Time: 02:19
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
if ($argc == 7) {
    $login = $argv[1];
    $pass = $argv[2];
    $mail = $argv[3];
    $url = $argv[4];
    $nomrtorrent = $argv[5];
    $scgi = $argv[6];
    $res = \model\mysql\Utilisateur::insertUtilisateurSysop($login, $pass, $mail);
    \model\simple\Console::println("Insertion de l'utilisateur");
    \model\simple\Console::println($res);
    $res = \model\mysql\Rtorrent::addRtorrentServeur1($nomrtorrent, $url);
    \model\simple\Console::println("Initialisation du rtorrent");
    \model\simple\Console::println($res);
    $res = \model\mysql\Rtorrents::addRtorrentUtilisateurScgi($login, $nomrtorrent, $scgi);
    \model\simple\Console::println("Ajout de la seedbox " . $nomrtorrent . " à " . $login . " scgi " . $scgi);
    \model\simple\Console::println($res);

} else {
    \model\simple\Console::println(basename(__FILE__) . " <login> <pass> <mail> <url accé a mediastorrent pour ce serveur sans le http exemple pour http://localhost/Mediastorrent il faut mettre localhost/Mediastorrent > <nomrtorrent> <portscgi le même qui est dans ~/.rtorrent.rc>");
}