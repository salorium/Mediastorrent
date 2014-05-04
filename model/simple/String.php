<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 12/03/14
 * Time: 20:31
 */

namespace model\simple;


class String extends \core\Model
{
    static function random($car, $espace = false)
    {
        $string = "";
        $chaine = "abcdefghijklmnpqrstuvwxyAZERTYUIOPQSDFGHJKLMWXCVBN0123456789" . ($espace ? " " : "");
        srand((double)microtime() * 1000000);
        for ($i = 0; $i < $car; $i++) {
            $string .= $chaine[rand() % strlen($chaine)];
        }
        return $string;
    }

    static function styleString($str)
    {
        return preg_replace("#([A-Z]+)#", '<span class="secondary">$1</span>', $str);
    }

    static function styleError($str)
    {
        return preg_replace("#(.+)#", '<span style="color:red;">$1</span>', $str);
    }

    static function styleSuccess($str)
    {
        return preg_replace("#(.+)#", '<span style="color:green;">$1</span>', $str);
    }
} 