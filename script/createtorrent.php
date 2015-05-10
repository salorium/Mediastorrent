<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 08/07/14
 * Time: 21:49
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

if (function_exists('ini_set')) {
    ini_set('display_errors', true);
    ini_set('log_errors', false);
}

$taskNo = $argv[1];
$utilisateur = $argv[2];
$portscgi = $argv[3];
\config\Conf::$portscgi = $portscgi;
$req = \core\Memcached::value($utilisateur, "task" . $taskNo);
if (!is_null($req)) {
    $request = unserialize($req);
    $announce_list = array();
    $trackers = array();
    $trackersCount = 0;
    if (isset($request['trackers'])) {
        $arr = explode("\r", $request['trackers']);
        foreach ($arr as $key => $value) {
            $value = trim($value);
            if (strlen($value)) {
                $trackers[] = $value;
                $trackersCount = $trackersCount + 1;
            } else {
                if (count($trackers) > 0) {
                    $announce_list[] = $trackers;
                    $trackers = array();
                }
            }
        }
    }
    if (count($trackers) > 0)
        $announce_list[] = $trackers;
    $path_edit = trim($request["repertoire"]);
    $piece_size = $request["piece"];
    $callback_log = create_function('$msg', '$fp=fopen("php://stderr","w"); fputs($fp, $msg."\n"); fclose($fp);');
    $callback_err = create_function('$msg', '$fp=fopen("php://stdout","w"); fputs($fp, $msg."\n"); fclose($fp);');

    if (count($announce_list) > 0) {
        $torrent = new \model\simple\Torrent($path_edit, $announce_list[0][0], $piece_size, $callback_log, $callback_err);
        if ($trackersCount > 1)
            $torrent->announce_list($announce_list);
    } else
        $torrent = new \model\simple\Torrent($path_edit, array(), $piece_size, $callback_log, $callback_err);
    if (isset($request['private']))
        $torrent->is_private(true);
    if (isset($request["seed"])) {
        $path_edit = dirname($path_edit);
        //$torrent->save($fname);
        \core\Memcached::value($utilisateur, "torrentfile" . $taskNo, $torrent->__toString(), 60 * 60);
        \model\xmlrpc\rTorrent::sendTorrent($torrent, true, $path_edit);
    } else
        \core\Memcached::value($utilisateur, "torrentfile".$taskNo, $torrent->__toString(), 60 * 60);
    exit(0);
}
exit(1);
?>