<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 22/03/14
 * Time: 03:35
 */

namespace controller\normal;


use core\Controller;

class Film extends Controller
{
    public $layout = "connecter";

    function nouveau()
    {
        $a = \model\mysql\Torrentfilm::getAllFilmUserDateDesc();
        //var_dump(json_encode($a));
        $this->set("film", json_encode($a));
        // die();
    }

} 