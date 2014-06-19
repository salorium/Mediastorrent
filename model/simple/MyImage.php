<?php
/**
 * Created by PhpStorm.
 * User: Salorium
 * Date: 02/12/13
 * Time: 14:26
 */

namespace model\simple;


class MyImage extends \core\Model
{
    private $width;
    private $height;
    private $image = false;

    function __construct($chemin)
    {
        if (filter_var($chemin, FILTER_VALIDATE_URL)) {
            //URL
            $headers = @get_headers($chemin);
            if (strpos($headers[0], '404') != false) {
                //NOT FOUND
                //throw new \Exception("Not Found Image");
                $this->image = new \Imagick();
                $this->image->newImage(600, 600, new \ImagickPixel('white'));
                /* Création d'un nouvel objet imagick */
                $im = new \Imagick();

                /* Création d'une nouvelle image. Elle sera utilisée comme masque de remplissage */
                $im->newPseudoImage(50, 100, "gradient:gray-black");

                /* Création d'un nouvel objet imagickdraw */
                $draw = new \ImagickDraw();

                /* On commence un nouveau masque nommé "gradient" */
                $draw->pushPattern('gradient', 0, 0, 50, 110);

                /* Ajout du dégradé sur le masque */
                $draw->composite(\Imagick::COMPOSITE_OVER, 0, 0, 50, 110, $im);

                /* Fermeture du masque */
                $draw->popPattern();

                /* Utilisation du masque nommé "gradient" comme remplissage */
                $draw->setFillPatternURL('#gradient');

                /* Définition de la taille du texte à 52 */
                $draw->setFontSize(92);
                $draw->setFont(ROOT . DS . 'font/comic.ttf');

                /* Ajout d'un texte */
                $draw->annotation(20, 100, "Not Found !");
                $this->image->drawImage($draw);
            }
        } else if (!file_exists($chemin)) {
            //throw new \Exception("Not Found Image");
            $this->image = new \Imagick();
            $this->image->newImage(600, 600, new \ImagickPixel('white'));
            /* Création d'un nouvel objet imagick */
            $im = new \Imagick();

            /* Création d'une nouvelle image. Elle sera utilisée comme masque de remplissage */
            $im->newPseudoImage(50, 100, "gradient:gray-black");

            /* Création d'un nouvel objet imagickdraw */
            $draw = new \ImagickDraw();

            /* On commence un nouveau masque nommé "gradient" */
            $draw->pushPattern('gradient', 0, 0, 50, 110);

            /* Ajout du dégradé sur le masque */
            $draw->composite(\Imagick::COMPOSITE_OVER, 0, 0, 50, 110, $im);

            /* Fermeture du masque */
            $draw->popPattern();

            /* Utilisation du masque nommé "gradient" comme remplissage */
            $draw->setFillPatternURL('#gradient');

            /* Définition de la taille du texte à 52 */
            $draw->setFontSize(92);
            $draw->setFont(ROOT . DS . 'font/comic.ttf');

            /* Ajout d'un texte */
            $draw->annotation(20, 100, "Not Found !");
            $this->image->drawImage($draw);
        }

        switch (strtolower(pathinfo($chemin, PATHINFO_EXTENSION))) {
            case "jpg":

            case "png":
                $this->image = new \Imagick($chemin);
                break;
        }
        $imageprops = $this->image->getImageGeometry();
        $this->width = $imageprops['width'];
        $this->height = $imageprops['height'];
        $this->image->setImageFormat("jpeg");
    }

    function getImageWidthFixed($width)
    {
        $nheight = $width / $this->width * $this->height;
        if (!($this->width <= $width && $this->height <= $nheight)) {
            $this->image->resizeImage($width, 0, (\Imagick::FILTER_LANCZOS), 1);
        }

        return $this->image->getImageBlob();
        return false;
    }

    function getImageHeightFixed($height)
    {
        $nwidth = $height / $this->height * $this->width;
        if (!($this->width <= $nwidth && $this->height <= $height)) {
            $this->image->resizeImage(0, $height, (\Imagick::FILTER_LANCZOS), 1);
        }

        return $this->image->getImageBlob();
        return false;
    }

    static function addTexte($image, $text, $fontfile, $fontsize)
    {
        $svg = '<?xml version="1.0" encoding="utf-8"?>

<!-- The icon can be used freely in both personal and commercial projects with no attribution required, but always appreciated.
You may NOT sub-license, resell, rent, redistribute or otherwise transfer the icon without express written permission from iconmonstr.com -->

<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.1//EN" "http://www.w3.org/Graphics/SVG/1.1/DTD/svg11.dtd">
<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="512px" height="512px" viewBox="0 0 512 512" xml:space="preserve">
<defs>
<linearGradient id="degrade" x1="100%" y1="0" x2="100%" y2="100%">
<stop offset="0%" style="stop-color:#898989; stop-opacity:0.2;"/>
<stop offset="40%" style="stop-color:#464646; stop-opacity:1;"/>
<stop offset="100%" style="stop-color:#111111; stop-opacity:0.7;"/>
</linearGradient>
<linearGradient id="degrade1" x1="100%" y1="0" x2="100%" y2="100%">
<stop offset="0%" style="stop-color:#111111; stop-opacity:0.7;"/>
<stop offset="40%" style="stop-color:#AAAAAA; stop-opacity:1;"/>
<stop offset="100%" style="stop-color:#F0F0F0; stop-opacity:0.2;"/>
</linearGradient>

<style type="text/css">
#stop1{ stop-color:chartreuse; stop-opacity:0.2; } #stop2{ stop-color:cornflowerblue; stop-opacity:1; } #stop3{ stop-color:chartreuse; stop-opacity:0.7; }

</style>
</defs>
<path style="fill:url(#degrade); stroke:#BBBBBB; stroke-width:2px;" id="video-icon" d="M50,60.345v391.311h412V60.345H50z M137.408,410.862H92.354v-38.747h45.055V410.862z M137.408,343.278
	H92.354v-38.747h45.055V343.278z M137.408,275.372H92.354v-38.747h45.055V275.372z M137.408,208.111H92.354v-38.748h45.055V208.111z
	 M137.408,140.526H92.354v-38.747h45.055V140.526z M337.646,410.862H177.961V275.694h159.685V410.862z M337.646,236.947H177.961
	V101.779h159.685V236.947z M423.253,410.862h-45.054v-38.747h45.054V410.862z M423.253,343.278h-45.054v-38.747h45.054V343.278z
	 M423.253,275.372h-45.054v-38.747h45.054V275.372z M423.253,208.111h-45.054v-38.748h45.054V208.111z M423.253,140.526h-45.054
	v-38.747h45.054V140.526z"/>
</svg>
';
        $im2 = new \Imagick ();
        $im2->setBackgroundColor(new \ImagickPixel('transparent'));
        $im2->readimageblob($svg);
        $im2->setImageFormat("png");
        $im2->adaptiveResizeImage(50, 50); /*Optional, if you need to resize*/
        //return $im2->getimageblob();
        $im = new \Imagick ();
        $im->newimage(100, 400, new \ImagickPixel('#999999'), "jpeg");
        $im->compositeimage($im2, \Imagick::COMPOSITE_DEFAULT, 25, 125);
        $widthmax = $im->getImageGeometry()["width"];
        $im1 = new \Imagick();

        /* Création d'une nouvelle image. Elle sera utilisée comme masque de remplissage */
        $im1->newPseudoImage(50, 10, "gradient:gray-black");
        $draw = new \ImagickDraw();
        /* On commence un nouveau masque nommé "gradient" */
        $draw->pushPattern('gradient', 0, 0, 50, 10);

        /* Ajout du dégradé sur le masque */
        $draw->composite(\Imagick::COMPOSITE_OVER, 0, 0, 50, 10, $im1);

        /* Fermeture du masque */
        $draw->popPattern();

        /* Utilisation du masque nommé "gradient" comme remplissage */
        $draw->setFillPatternURL('#gradient');

        /* Font properties */
        $draw->setFont($fontfile);
        $draw->setFontSize($fontsize);
        $draw->setGravity(\Imagick::GRAVITY_NORTH);
        $words = explode(' ', $text);

        //Test si la fontsize n'est pas trop grosse pour un mot
        $i = 0;
        while ($i < count($words)) {
            $lineSize = $im->queryfontmetrics($draw, $words[$i])["textWidth"];
            if ($lineSize < $widthmax) {
                $i++;
            } else {
                $fontsize--;
                $draw->setFontSize($fontsize);
            }
        }
        $res = $words[0];
        for ($i = 1; $i < count($words); $i++) {
            $lineSize = $im->queryfontmetrics($draw, $res . " " . $words[$i]);
            if ($lineSize["textWidth"] < $widthmax) {
                $res .= " " . $words[$i];
            } else {
                $res .= "\n" . $words[$i];
            }
        }
        /* Create text */
        $im->annotateImage($draw, 0, 0, 0, $res);
        return $im->getimageblob();

    }

    static function makeTextBlockCenter($text, $fontfile, $fontsize, $img)
    {
        $black = imagecolorallocate($img, 0x00, 0x00, 0x00);
        $width = imagesx($img);
        $words = explode(' ', $text);
        //Teste si la fontsize n'est pas trop grosse pour un mot

        $i = 0;
        while ($i < count($words)) {
            $lineSize = imagettfbbox($fontsize, 0, $fontfile, $words[$i]);
            if ($lineSize[2] - $lineSize[0] < $width) {
                $i++;
            } else {
                $fontsize--;
            }
        }
        $lines = array($words[0]);
        $currentLine = 0;
        $lineSize = imagettfbbox($fontsize, 0, $fontfile, $words[0]);
        $currentwidth = $lineSize[2] - $lineSize[0];
        $currentY = 20;
        for ($i = 1; $i < count($words); $i++) {
            $lineSize = imagettfbbox($fontsize, 0, $fontfile, $lines[$currentLine] . ' ' . $words[$i]);
            if ($lineSize[2] - $lineSize[0] < $width) {

                $lines[$currentLine] .= ' ' . $words[$i];
                $currentwidth = $lineSize[2] - $lineSize[0];
            } else {
                if ($width - $currentwidth != 0) {

                    $px = ($width - $currentwidth) / 2;
                } else {
                    $px = 0;
                }
                imagefttext($img, $fontsize, 0, $px, $currentY, $black, $fontfile, $lines[$currentLine]);
                $currentY += $fontsize + 5;
                $currentLine++;
                $lines[$currentLine] = $words[$i];
            }
        }
        $lineSize = imagettfbbox($fontsize, 0, $fontfile, $lines[$currentLine]);
        $currentwidth = $lineSize[2] - $lineSize[0];
        $px = ($width - $currentwidth) / 2;
        imagefttext($img, $fontsize, 0, $px, $currentY, $black, $fontfile, $lines[$currentLine]);
        return $img;
    }
} 