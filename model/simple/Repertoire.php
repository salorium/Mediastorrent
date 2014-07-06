<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 06/07/14
 * Time: 19:06
 */

namespace model\simple;


class Repertoire extends \core\Model
{
    static function getFindAll()
    {
        $recmd = \model\simple\Console::execute('find /home/' . \config\Conf::$user['user']->login . '/rtorrent/data/ -printf "%p|%s|%y\n" | sort -t \'|\' -k1 | awk -F \'|\' \'{gsub(/\/home\/' . \config\Conf::$user['user']->login . '\/rtorrent\/data\//,"",$1); print "{name:\""$1"\",taille:"$2",type:\""$3"\"}," }\'');
        var_dump($recmd);
    }
} 