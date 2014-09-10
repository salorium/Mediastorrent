<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 11/09/14
 * Time: 00:48
 */

namespace controller\normal;


class Cronroot extends \core\Controller
{
    function check($id)
    {
        $this->set("fini", \model\mysql\Cronroot::estFini($id));

    }
} 