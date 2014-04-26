<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 26/04/14
 * Time: 13:47
 */

namespace controller;


class Install extends  \core\Controller{
    function index(){
        $this->layout = "install";
        $memachedload = extension_loaded("memcached");
        $this->set(array(
            "memcached"=>$memachedload
        ));
    }
} 