<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 25/10/13
 * Time: 11:47
 * To change this template use File | Settings | File Templates.
 */

namespace core;


/**
 * Class Router
 * @package core
 */
class  Router
{

    static $routesredir = array();

    /**
     * Permet de parser une url
     * @param $url
     * @return table contenant les paramètres
     */
    static function parse($url, $request)
    {
        $ext = pathinfo($url, PATHINFO_EXTENSION);
        $url = trim(preg_replace("#\." . $ext . "$#i", "", $url), "/");
        if (empty($url)) {
            $url = self::$routesredir[\config\Conf::$user["roletxt"]]["/"]["url"];
        }
        $params = explode('/', $url);
        $params = str_replace("\\", "/", $params);
        $request->controller = $params[0];
        $request->action = isset($params[1]) ? $params[1] : "index";
        $request->params = array_slice($params, 2);
        $request->rendu = $ext === "" ? "html" : $ext;
    }

    static function connect($role, $redir, $url)
    {
        $r = array();
        $r["url"] = $url;
        self::$routesredir[$role][$redir] = $r;
    }

    static function url($url)
    {
        return BASE_URL . $url;
    }
}