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
        $tmp = array();
        foreach ($a as $v) {
            $t = null;
            $t = json_decode($v->infos);
            $t->id = $v->id;
            $t->poster = $v->poster;
            $t->backdrop = $v->backdrop;
            $tmp[] = $t;
        }
        $this->set("film", $tmp);
        // die();
    }

} 