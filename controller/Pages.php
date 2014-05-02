<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 25/10/13
 * Time: 11:58
 * To change this template use File | Settings | File Templates.
 */

namespace controller;


use core\Controller;

class Pages extends Controller
{
    function view($nom)
    {
        $this->set(array(
            "phrase" => "Salut",
            "nom" => $nom
        ));
        // $rtorrent = new \model\mysql\Rtorrent();
        //  $r = new \model\xmlrpc\rTorrent();
    }
}