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
        //$url = urldecode($url);
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
        //$url = urldecode($url);
        $myimage = new \model\simple\MyImage($url);

        $this->set(array(
            "url" => $url,
            "size" => $size,
            "image" => $myimage->getImageHeightFixed($size)
        ));
        $this->render("index");
    }

    function noimage($titre)
    {
        $im = new \Imagick (ROOT . DS . "webroot/images/no-poster-w92.jpg");
        $draw = new ImagickDraw();
        $draw->setFillColor('black');

        /* Font properties */
        $draw->setFont('Bookman-DemiItalic');
        $draw->setFontSize(30);

        /* Create text */
        $im->annotateImage($draw, 10, 45, 0, 'The quick brown fox jumps over the lazy dog');

        //$im = \model\simple\MyImage::makeTextBlockCenter($titre, ROOT . DS . "font" . DS . "comic.ttf", 10, $im);
        header('Content-Type: image/jpg');

        imagejpeg($im);
        imagedestroy($im);
        exit;
    }
} 