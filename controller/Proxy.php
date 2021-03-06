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
        /*$im = \imagecreatefromjpeg($url);
        ob_start();
        imagejpeg($im, NULL, 100);
        $img = \ob_get_clean();
        $this->set(array(
            "url" => $url,
            "image" => $img
        ));
        $this->render("index");*/
        $myimage = new \model\simple\MyImage($url);
        //var_dump($url);
        //die();//*/
        $this->set(array(
            "url" => $url,
            "image" => $myimage->getImage()
        ));
        $this->render("index");
    }

    function imageSetWidth($url, $size)
    {
        //$url = urldecode($url);
        //echo $url;

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
        /*$im = new \Imagick (ROOT . DS . "webroot/images/no-poster-w92.jpg");
        $draw = new \ImagickDraw();
        $draw->setFillColor('white');

        /* Font properties */
        /*$draw->setFont(ROOT . DS . "font" . DS . "comic.ttf");
        $draw->setFontSize(10);
        $draw->setGravity(\Imagick::GRAVITY_CENTER);
        $draw->setViewbox(0, 10, 0, 10);

        /* Create text */
        //$im->annotateImage($draw, 0, 0, 0, "Lorem ipsum\n dolor\n sit amet,\n consectetur adipisicing elit.\n Ea eaque earum eligendi labore mollitia voluptas. Aspernatur consectetur deleniti doloremque labore non, nulla placeat, quas quasi quisquam sequi totam veniam. Fuga.");
        //$im = \model\simple\MyImage::makeTextBlockCenter($titre, ROOT . DS . "font" . DS . "comic.ttf", 10, $im);
        $this->set(array(
            "image" => \model\simple\MyImage::nonImageAddTexte($titre, ROOT . DS . "font" . DS . "comic.ttf", 30)
        ));
        $this->render("index");
    }
} 