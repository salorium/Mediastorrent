<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 30/04/14
 * Time: 02:07
 */

namespace model\simple;


use config\Conf;
use core\Model;

class Console extends Model
{
    static function println($str)
    {
        if (is_bool($str)) {
            $str = ($str ? "Ok" : "No ok");
        }
        if (is_array($str)) {
            $str = json_encode($str);
        }
        if (Conf::$debuglocal)
            if (Conf::$debuglocalfile) {
                file_put_contents(LOG, "[" . date("j/n/Y G:i:s") . "] " . $str . "\n", FILE_APPEND);
            } else {
                echo "[" . date("j/n/Y G:i:s") . "] " . $str . "\n";
            }

    }
} 