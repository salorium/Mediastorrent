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
        if (Conf::$debuglocal)
            echo "[" . date("j/n/Y G:i:s") . "] " . $str . "\n";
    }
} 