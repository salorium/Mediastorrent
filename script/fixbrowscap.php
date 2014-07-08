<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 09/07/14
 * Time: 01:18
 */
ini_set('memory_limit', '-1');
$content = file('http://browscap.org/stream?q=Full_PHP_BrowsCapINI');
//var_dump($content);
foreach ($content as &$row)
    if ($row[0] == '[')
        $row = str_replace(';', '\;', $row);

file_put_contents('/etc/php/conf.d/browscap.ini', $content);