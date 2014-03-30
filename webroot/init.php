<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 25/03/14
 * Time: 10:26
 */
define('WEBROOT',__DIR__);
define('ROOT',dirname(WEBROOT));
define('DS',DIRECTORY_SEPARATOR);
define('CORE',ROOT.DS.'core');
define('BASE_URL',"http".($_SERVER["SERVER_PORT"] == 80 ? "":"s")."://".$_SERVER["HTTP_HOST"].dirname(dirname($_SERVER["SCRIPT_NAME"])));

function __autoload($class_name) {
    $filename = ROOT.DS.str_replace("\\",DS,$class_name).".php";
    if (file_exists($filename)){
        require_once $filename;
    }

}

$theSettings = \model\xmlrpc\rTorrentSettings::get(5001,true);
var_dump($theSettings);
$req = new \model\xmlrpc\rXMLRPCRequest(5001, array(
    $theSettings->getOnFinishedCommand(array("seedingtime",
        \model\xmlrpc\rTorrentSettings::getCmd(5001,'d.set_custom').'=seedingtime,"$'.\model\xmlrpc\rTorrentSettings::getCmd(5001,'execute_capture').'={date,+%s}"')),
    $theSettings->getOnInsertCommand(array("addtime",
        \model\xmlrpc\rTorrentSettings::getCmd(5001,'d.set_custom').'=addtime,"$'.\model\xmlrpc\rTorrentSettings::getCmd(5001,'execute_capture').'={date,+%s}"')),

    $theSettings->getOnHashdoneCommand(array("seedingtimecheck",
        \model\xmlrpc\rTorrentSettings::getCmd(5001,'branch=').'$'.\model\xmlrpc\rTorrentSettings::getCmd(5001,'not=').'$'.\model\xmlrpc\rTorrentSettings::getCmd(5001,'d.get_complete=').',,'.
        \model\xmlrpc\rTorrentSettings::getCmd(5001,'d.get_custom').'=seedingtime,,"'.\model\xmlrpc\rTorrentSettings::getCmd(5001,'d.set_custom').'=seedingtime,$'.\model\xmlrpc\rTorrentSettings::getCmd(5001,'d.get_custom').'=addtime'.'"')),
));
if($req->success())
    echo "ok";
else
    echo "error";
echo $req;
?>