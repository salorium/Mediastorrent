<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 30/07/15
 * Time: 14:02
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
use \model\mysql\Torrentserie as Torrentfilm;

\config\Conf::$debuglocalfile = false; //retour visuel
$torrentfilm = Torrentfilm::getAll();
foreach ($torrentfilm as $v) {
    //var_dump($v);
    \config\Conf::$userscgi = $v->login;
    $hashtorrentselectionne = $v->hashtorrent;
    $nofile = $v->numfile;
    $mediasinfo = json_decode($v->mediainfo, true);

    $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi,
        new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "f.frozen_path", array($hashtorrentselectionne . ":f" . $nofile)));
    if ($req->success()) {
        $filename = $req->val[0];
        if ($filename == '') {
            $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$userscgi, array(
                new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.open", $hashtorrentselectionne),
                new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "f.frozen_path", array($hashtorrentselectionne . ":f" . $nofile)),
                new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$userscgi, "d.close", $hashtorrentselectionne)));
            if ($req->success())
                $filename = $req->val[1];
        }
        $mediasinfo["taille"] = filesize($filename);
        $mediasinfo["filename"] = $filename;
        \model\simple\Console::println($filename);
        \model\simple\Console::println($v->updateMediainfo($mediasinfo));

    }


}


