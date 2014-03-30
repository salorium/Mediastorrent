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
            if (strpos($headers[0],'404') === true){
               //NOT FOUND
               throw new \Exception("Not Found Image");
            }
        }else if (!file_exists($chemin)){
            throw new \Exception("Not Found Image");
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