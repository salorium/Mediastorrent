<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 25/10/13
 * Time: 11:40
 * To change this template use File | Settings | File Templates.
 */

namespace core;


/**
 * Class Request
 * @package core
 */
class Request
{
    public $url; // URL appellé par l'utilisateur
    public $controller;
    public $action;
    public $params;

    function __construct()
    {
        $url = str_replace($_SERVER["DOCUMENT_ROOT"], "", (isset ($_SERVER["PATH_INFO"]) ? $_SERVER["PATH_INFO"] : ""));
        $this->url = $url == "" ? "/" : $url;

    }
}