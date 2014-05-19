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
    public static $query = array();

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
                echo $str . "\n";
            }

    }

    static function saisieString($str)
    {
        echo $str . "\n";
        $reponse = fgets(STDIN);
        return trim($reponse);
    }

    static function execute($cmd)
    {
        exec(escapeshellcmd($cmd), $output, $error);
        self::$query[] = array($cmd, $output, $error);
        return array($error, implode("", $output));
    }
} 