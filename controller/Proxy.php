<?php
/**
 * Created by PhpStorm.
 * User: Salorium
 * Date: 29/11/13
 * Time: 23:38
 */

namespace controller;


use core\Controller;

class Proxy extends Controller
{
    function image($url)
    {
        $im = \imagecreatefromjpeg($url);
        ob_start();
        imagejpeg($im, NULL, 100);
        $img = \ob_get_clean();
        $this->set(array(
            "url" => $url,
            "image" => $img
        ));
        $this->render("index");
    }

    function imageSetWidth($url, $size)
    {
        $myimage = new \model\simple\MyImage($url);

        $this->set(array(
            "url" => $url,
            "size" => $size,
            "image" => $myimage->getImageWidthFixed($size)
        ));
        $this->render("index");
    }

    function imageSetHeight($url, $size)
    {
        $myimage = new \model\simple\MyImage($url);

        $this->set(array(
            "url" => $url,
            "size" => $size,
            "image" => $myimage->getImageHeightFixed($size)
        ));
        $this->render("index");
    }
} 