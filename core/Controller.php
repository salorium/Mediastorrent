<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 25/10/13
 * Time: 11:57
 * To change this template use File | Settings | File Templates.
 */

namespace core;


use config\Conf;

class Controller
{
    public $request;
    private $vars = array();
    private $rendered = false;
    public $layout = "default";
    public $debug = null;

    function __construct($request, $debug)
    {
        $this->request = $request;
        $this->debug = $debug;
    }

    public function render($view)
    {
        if ($this->rendered) {
            return false;
        }
        switch ($this->request->rendu) {
            case "xml":
                $this->renderXml($view);
                break;
            case "json":
                $this->renderJson($view);
                break;
            case "txt":
                $this->renderTextPlain($view);
                break;
            case "jpg":
                $this->renderJpeg($view);
                break;
            case "png":
                $this->renderPng($view);
                break;
            default:
                $this->renderHtml($view);
                break;
        }
        $this->rendered = true;
    }

    private function renderHtml($view)
    {
        \extract($this->vars);
        if (\strpos($view, "/") === 0) {
            $view = ROOT . DS . "view" . DS . "html" . DS . $view . ".php";
        } else {
            if (Conf::$rolevue === "visiteur") {
                $view = ROOT . DS . "view" . DS . "html" . DS . $this->request->controller . DS . $view . ".php";
            } else {
                $view = ROOT . DS . "view" . DS . "html" . DS . $this->request->controller . DS . Conf::$rolevue . DS . $view . ".php";
            }

        }
        //Affichage du contenu de la page !!!
        \ob_start();
        require $view;
        $content_for_layout = \ob_get_clean();
        //Affichage des performance
        \ob_start();
        $t = $this->debug->get_perf();
        extract($t);
        require ROOT . DS . "view" . DS . "html" . DS . "debug" . DS . "performance.php";
        $debug_performance_for_layout = \ob_get_clean();

        if (\config\Conf::$debug) {
            \ob_start();
            //Affichage de l'icon du DEBUG
            $debugicon = $this->debug->showIcon();
            require ROOT . DS . "view" . DS . "html" . DS . "debug" . DS . "icon.php";
            $debug_icon_for_layout = \ob_get_clean();

            //Affichage des détails du DEBUG (Contenu)

            \ob_start();
            $data = \core\Debug::$fatal;
            if (is_array($data))
                require ROOT . DS . "view" . DS . "html" . DS . "debug" . DS . "fatals.php";

            $data = \core\Debug::$error;
            if (is_array($data))
                require ROOT . DS . "view" . DS . "html" . DS . "debug" . DS . "erreurs.php";

            $data = \core\Mysqli::$query;
            $time = \core\Mysqli::$time;
            if (is_array($data))
                require ROOT . DS . "view" . DS . "html" . DS . "debug" . DS . "mysqli.php";
            if (extension_loaded("memcached")) {
                $data = \core\Memcached::$request;
                $time = \core\Memcached::$time;
                if (is_array($data))
                    require ROOT . DS . "view" . DS . "html" . DS . "debug" . DS . "memcached.php";
            }
            $debug_contenu_for_layout = \ob_get_clean();

            \ob_start();
            require ROOT . DS . "view" . DS . "html" . DS . "debug" . DS . "details.php";
            $debug_detail_for_layout = \ob_get_clean();

        }

        //Chargement des js
        $loadjavascript_for_layout = "";
        $initjavascript_for_layout = "";
        foreach (LoaderJavascript::$javascriptName as $k => $data) {

            \ob_start();
            require ROOT . DS . "view" . DS . "html" . DS . "layout" . DS . "loadjavascript.php";
            $loadjavascript_for_layout .= \ob_get_clean();
            foreach ($data as $kk => $js) {
                \ob_start();
                require ROOT . DS . "view" . DS . "html" . DS . "layout" . DS . "initjavascript.php";
                $initjavascript_for_layout .= \ob_get_clean();
            }
        }


        //Rendu final !!!
        //Sélection du layout

        require ROOT . DS . "view" . DS . "html" . DS . "layout" . DS . $this->layout . ".php";
    }

    private function renderLayout()
    {

    }

    private function renderJson($view)
    {
        \header("Cache-Control: no-cache, must-revalidate");
        \header("Expires: Mon, 10 Jul 1990 05:00:00 GMT");
        \header('Content-Type: application/json');
        if (\strpos($view, "/") === 0) {
            list($root, $type) = \explode("/", \trim($view, "/"));
            $ancvars = $this->vars;
            $this->vars = array();
            $this->vars[$root] = $ancvars;
            $this->vars[$root]["type"] = $type;
        }
        $this->vars["showdebugger"] = $this->debug->showIcon();
        $this->vars["debuggerfatal"] = \core\Debug::$fatal;
        $this->vars["debuggererreur"] = \core\Debug::$error;
        if (\config\Conf::$debug) {


            $this->vars["debuggermysql"] = \core\Mysqli::$query;
        //$this->vars["debuggerxmlrpc"]= array(\model\xmlrpc\rXMLRPCRequest::$time,\model\xmlrpc\rXMLRPCRequest::$query);
        //$this->vars["debuggerlogtime"] = \core\Debug::$timelog;
        //$this->vars["scgi"] = \config\Conf::$portscgi;
        }
        $this->vars["perf"] = $this->debug->get_perf();
        echo \json_encode($this->vars);

    }

    private function renderXml($view)
    {
        //\header('Content-Type: text/xml');
        //\header("Cache-Control: no-cache, must-revalidate");
        //\header("Expires: Mon, 10 Jul 1990 05:00:00 GMT");
        $tmp = $this->debug->showIcon();
        if (!is_null($tmp))
            $this->vars["showdebugger"] = $tmp;
        $this->vars["perf"] = $this->debug->get_perf();
        $this->vars = json_decode(json_encode($this->vars), true);
        $xml = new SimpleXMLElement('<root/>');
        \array_walk_recursive($this->vars, array($this, 'parserXml'), $xml);
        die();
        print $xml->asXML();
    }

    private function parserXml($item, $key, &$xml)
    {
        echo $key . "<br>";
        var_dump($item);
        echo "===============<br>";
    }

    private function renderTextPlain($view)
    {
        \header("Content-Type: text/plain");
        $view = ROOT . DS . "view" . DS . "txt" . DS . $this->request->controller . DS . $view . ".php";
        $tmp = $this->debug->showIcon();
        if (!is_null($tmp))
            $this->vars["showdebugger"] = $tmp;
        $this->vars["perf"] = $this->debug->get_perf();
        if (\file_exists($view)) {
            \extract($this->vars);
            require $view;
        } else {
            \print_r($this->vars);
        }

    }

    private function renderJpeg($view)
    {
        if (isset($this->vars["image"])) {
            header('Content-Type: image/jpeg');
            echo $this->vars["image"];
        }
    }

    private function renderPng($view)
    {
        if (isset($this->vars["image"])) {
            header('Content-Type: image/png');
            echo $this->vars["image"];
        }
    }

    public function set($key, $value = null)
    {
        if (\is_array($key)) {
            $this->vars += $key;
        } else {
            $this->vars[$key] = $value;
        }
    }

    public function loadModel($name)
    {
        $this->$name = new $name;
    }
}