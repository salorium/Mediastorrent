<?php
/**
 * Created by PhpStorm.
 * User: Salorium
 * Date: 02/12/13
 * Time: 14:26
 */

namespace model\simple;


class MyImage extends \core\Model {
    private $width;
    private $height;
    private $image = false;
    function __construct($chemin)
    {
        if (filter_var($chemin, FILTER_VALIDATE_URL)){
            //URL
            $headers = @get_headers($chemin);
            if (strpos($headers[0],'404') != false){
               //NOT FOUND
               //throw new \Exception("Not Found Image");
                $this->image = new \Imagick(  );
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
                $draw->setFont(ROOT.DS.'font/comic.ttf');

                /* Ajout d'un texte */
                $draw->annotation(20,100, "Not Found !");
                $this->image->drawImage($draw);
            }
        }else if (!file_exists($chemin)){
            //throw new \Exception("Not Found Image");
            $this->image = new \Imagick(  );
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
            $draw->setFont(ROOT.DS.'font/comic.ttf');

            /* Ajout d'un texte */
            $draw->annotation(20,100, "Not Found !");
            $this->image->drawImage($draw);
        }

        switch (strtolower(pathinfo($chemin,PATHINFO_EXTENSION)) ){
            case "jpg":

            case "png":
                $this->image = new \Imagick( $chemin );
                break;
        }
        $imageprops = $this->image->getImageGeometry();
        $this->width = $imageprops['width'];
        $this->height = $imageprops['height'];
        $this->image->setImageFormat("jpeg");
    }

    function getImageWidthFixed($width){
        $nheight = $width / $this->width * $this->height;
        if (! ($this->width  <=  $width && $this->height <= $nheight)){
            $this->image->resizeImage($width,0,(\Imagick::FILTER_LANCZOS) , 1);
        }

        return $this->image->getImageBlob();
        return false;
    }

    function getImageHeightFixed($height){
        $nwidth = $height / $this->height * $this->width;
        if (! ($this->width  <=  $nwidth && $this->height <= $height)){
            $this->image->resizeImage(0,$height,(\Imagick::FILTER_LANCZOS) , 1);
        }

        return $this->image->getImageBlob();
        return false;
    }


} 