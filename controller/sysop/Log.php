<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 22/07/15
 * Time: 16:21
 */

namespace controller\sysop;


class Log extends \core\Controller
{
    public $layout = "connecter";

    function dl()
    {
        $this->set("txt", file_get_contents(ROOT . DS . "log" . DS . "download.txt"));

    }

}