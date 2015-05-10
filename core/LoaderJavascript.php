<?php
/**
 * Created by PhpStorm.
 * User: Salorium
 * Date: 06/12/13
 * Time: 17:00
 */

namespace core;


class LoaderJavascript
{
    static $javascriptName = array();

    static function add($name, $fonction = null, $args = null)
    {
        $t ["name"] = $name;
        if (!is_null($fonction)) {
            $t["fonction"] = $fonction;
            if (!is_null($args)) {
                $t["args"] = $args;
            }
        }
        self::$javascriptName[$name][] = $t;
    }
} 