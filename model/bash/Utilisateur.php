<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 17/05/14
 * Time: 09:44
 */

namespace model\bash;


class Utilisateur extends \core\Model
{
    static function addRtorrent($login, $scgi)
    {
        //Voir si l'utilisateur existe sur le system
        exec(escapeshellcmd("id " . $login), $output, $error);
        var_dump($error);
    }
} 