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
        $im = new \Imagick ($image);
        $widthmax = $im->getImageGeometry();
        $draw = new \ImagickDraw();
        $draw->setFillColor('white');

        /* Font properties */
        $draw->setFont($fontfile);
        $draw->setFontSize($fontsize);
        $draw->setGravity(\Imagick::GRAVITY_CENTER);
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
            var_dump($lineSize);
            echo $widthmax . "<br>";
            if ($lineSize["textWidth"] < $widthmax) {
                $res .= " " . $words[$i];
                echo "ESSPACE<br>";
            } else {
                $res .= "\nAAA" . $words[$i];
                echo "ENTER<br>";
            }
        }
        /* Create text */
        var_dump($res . "\n");
        die();
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