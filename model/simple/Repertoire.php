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
        $recmd = \model\simple\Console::executeBrut('find /home/' . \config\Conf::$user['user']->login . '/rtorrent/data/ -printf "%p|%s|%y\n" | sort -t \'|\' -k1 | awk -F \'|\' \'{gsub(/\/home\/' . \config\Conf::$user['user']->login . '\/rtorrent\/data\//,"",$1); print "[\""$1"\","$2",\""$3"\"]" }\'');
        if ($recmd[0] !== 0) {
            trigger_error("Impossible de liste le repertoire de l'utilisateur");
            $recmd[1][] = '';
        }
        $liste = "[" . implode(",", $recmd[1]) . "]";
        return json_decode(utf8_encode($liste), true);
    }
} 