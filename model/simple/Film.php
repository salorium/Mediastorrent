<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 20/06/14
 * Time: 23:32
 */

namespace model\simple;


class Film extends \core\Model
{
    static function getBackdrop($id)
    {
        $backdrop = ROOT . DS . "cache" . DS . "film" . DS . "backdrop";
        if (!is_dir($backdrop))
            mkdir($backdrop, 0777, true);
        if (!file_exists($backdrop . DS . $id . ".jpg")) {
            var_dump(\model\mysql\Film::getBackdrop($id));
        }
        die();
        return $backdrop . DS . $id . ".jpg";
    }

    static function getBackdropSetWidth($id, $width)
    {

    }
} 