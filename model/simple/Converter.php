<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 19/07/14
 * Time: 00:56
 */

namespace model\simple;


class Converter extends \core\Model
{
    /**
     * bytes: function (bt, p) {
     * p = (p == null) ? 1 : p;
     * var a = new Array("o", "Ko", "Mo", "Go", "To", "Po");
     * var ndx = 0;
     * if (bt == 0)
     * ndx = 1;
     * else {
     * if (bt < 1024) {
     * bt /= 1024;
     * ndx = 1;
     * }
     * else {
     * while (bt >= 1024) {
     * bt /= 1024;
     * ndx++;
     * }
     * }
     * }
     * return(this.round(bt, p) + " " + a[ndx]);
     * },
     */
    static function bytes($bt, $p = null)
    {
        $p = (is_null($p) ? 1 : $p);
        $a = ["o", "Ko", "Mo", "Go", "To", "Po"];
        $ndx = 0;
        if ($bt == 0) {
            $ndx = 1;
        } else if ($bt < 1024) {
            $bt /= 1024;
            $ndx = 1;
        } else {
            while ($bt >= 1024) {
                $bt /= 1024;
                $ndx++;
            }
        }
        return round($bt, $p) . " " . $a[$ndx];
    }
} 