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
$torrentfilm = \model\mysql\Torrentserie::getAll();
foreach ($torrentfilm as $v) {
    //var_dump($v);
    \model\simple\Console::println($v->id);

    $mediasinfo = json_decode($v->mediainfo, true);
    $m = new \model\simple\Mediainfo($mediasinfo["filename"]);
    \model\simple\Console::println($v->updateMediainfo($m->getFormatVideo()));
}


