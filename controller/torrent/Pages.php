<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 10/03/14
 * Time: 13:47
 */

namespace controller\torrent;

use core\Controller;

class Pages extends Controller
{
    function torrent($nom)
    {
        $this->set(array(
            "torrent" => $nom
        ));
    }
} 