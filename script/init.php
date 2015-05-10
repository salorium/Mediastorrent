<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 30/04/14
 * Time: 00:37
 */
/**
 * Todo: A supprimer dans la version final plus nécessaire
 *
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

$portscgi = trim(file_get_contents("/home/" . $argv[1] . "/.scgi.txt"));
usleep(10000);
define('LOG', ROOT . DS . "log" . DS . $portscgi . "_init.log");
\model\simple\Console::println("Début");
$theSettings = \model\xmlrpc\rTorrentSettings::get($portscgi, true);
$req = new \model\xmlrpc\rXMLRPCRequest($portscgi, array(
    $theSettings->getOnFinishedCommand(array("seedingtime",
        \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.set_custom') . '=seedingtime,"$' . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'execute_capture') . '={date,+%s}"')),
    $theSettings->getOnInsertCommand(array("addtime",
        \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.set_custom') . '=addtime,"$' . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'execute_capture') . '={date,+%s}"')),

    $theSettings->getOnHashdoneCommand(array("seedingtimecheck",
        \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'branch=') . '$' . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'not=') . '$' . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.get_complete=') . ',,' .
        \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.get_custom') . '=seedingtime,,"' . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.set_custom') . '=seedingtime,$' . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.get_custom') . '=addtime' . '"')),

    \model\xmlrpc\rTorrentSettings::get($portscgi)->getOnEraseCommand(array('erasedata',
        \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'branch=') . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.get_custom1') . '=,"' .
        \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'execute') . '={rm,-r,$' . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.get_base_path') . '=}"')),
    $theSettings->getOnFinishedCommand(array('addbibliotheque',
        \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'execute') . '={' . 'php,' . ROOT . DS . 'script/addbibliotheque.php,' . $portscgi . ',$' . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.get_hash') . '=,$' . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.get_base_path') . '=,$' .
        \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.get_base_filename') . '=,$' . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.is_multi_file') . '=,$' . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.get_custom') . '=clefunique,$' . \model\xmlrpc\rTorrentSettings::getCmd($portscgi, 'd.get_custom') . '=typemedias}'
    ))
));
if ($req->run()) {
    \model\simple\Console::println("ok");
    exit(0);
} else {
    \model\simple\Console::println("Non ok");
    \model\simple\Console::println($req->val);
    exit(1);
}


?>