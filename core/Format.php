<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 26/09/13
 * Time: 13:02
 * To change this template use File | Settings | File Templates.
 */
namespace core;
class Format
{
    public static function get_size($Size, $Levels = 2)
    {
        $Units = array(' o', ' Ko', ' Mo', ' Go', ' To', ' Po', ' Eo', ' Zo', ' Yo');
        $Size = (double)$Size;
        for ($Steps = 0; abs($Size) >= 1024; $Size /= 1024, $Steps++) {
        }
        if (func_num_args() == 1 && $Steps >= 4) {
            $Levels++;
        }
        return number_format($Size, $Levels) . $Units[$Steps];
    }
}