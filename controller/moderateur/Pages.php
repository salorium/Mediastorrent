<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 10/03/14
 * Time: 13:47
 */

namespace controller\moderateur;

use core\Controller;

class Pages extends Controller
{
    function moderateur($nom)
    {


        $this->set(array(
            "modo" => $nom,
            "class" => __NAMESPACE__
        ));
    }
} 