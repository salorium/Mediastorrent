<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 06/07/14
 * Time: 03:03
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

$path_edit = "/home/salorium/rtorrent/data/Alaska.La.ruee.vers.l.or.S04E03.avi";
$piece_size = "512";
$callback_log = create_function('$msg', '$fp=fopen("php://stderr","w"); fputs($fp, $msg."\n"); fclose($fp);');
$callback_err = create_function('$msg', '$fp=fopen("php://stdout","w"); fputs($fp, $msg."\n"); fclose($fp);');


$torrent = new \model\simple\Torrent($path_edit, array(), $piece_size, $callback_log, $callback_err);
$torrent->is_private(true);
var_dump($torrent->info['name']);
?>
