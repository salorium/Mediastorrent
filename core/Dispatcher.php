<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 25/10/13
 * Time: 11:23
 * To change this template use File | Settings | File Templates.
 */
namespace core;

use config\Conf;

class Dispatcher
{
    private $request;
    private $debug;

    function __construct()
    {
        LoaderJavascript::add("debug", "controller.init", Conf::$debug);
        LoaderJavascript::add("test", "controller.init");
        $this->debug = new Debug($this);
        $this->debug->handle_errors();
        if (!Conf::$install)
            $this->roleUser();
        $this->request = new Request();
        Router::parse($this->request->url, $this->request);
    }

    function load()
    {
        $num = Conf::$user["role"];
        $compteurarray = null;
        do {
            do {
                $role = Conf::$numerorole[$num];
                if (is_array($role)) {
                    if (is_null($compteurarray)) {
                        $compteurarray = 0;
                    }
                    $r = $role;
                    $role = $role[$compteurarray];
                    $compteurarray++;
                    if ($compteurarray == count($r)) {
                        $compteurarray = null;
                        $num--;
                    }

                } else {
                    $num--;
                }
                if ($role === \config\Conf::$numerorole[1]) {
                    $cname = '\controller\\' . ucfirst($this->request->controller);
                } else {
                    $cname = '\controller\\' . strtolower($role) . '\\' . ucfirst($this->request->controller);
                }

            } while (!file_exists(ROOT . DS . str_replace("\\", DS, $cname) . ".php") && $num > 0);

            Conf::$rolevue = strtolower($role);
            $controller = new $cname($this->request, $this->debug);
        } while (!in_array($this->request->action, get_class_methods($controller)) && $num > 0);
        if (!in_array($this->request->action, get_class_methods($controller))) {
            trigger_error("Le controller " . $this->request->controller . " n'a pas de méthode " . $this->request->action);
            $this->error("Le controller " . $this->request->controller . " n'a pas de méthode " . $this->request->action);
        }
        call_user_func_array(array($controller, $this->request->action), $this->request->params);
        $controller->render($this->request->action);
    }

    function roleUser()
    {
        foreach (Conf::$numerorole as $k => $v) {
            if (is_array($v)) {
                foreach ($v as $kk => $vv) {
                    Conf::$rolenumero [$vv] = $k;
                }
            } else {
                Conf::$rolenumero [$v] = $k;
            }

        }
        /*$a["zzz"]="sssd";
        $a["zdz"]= new \model\xmlrpc\rTorrent();
        //Memcached::value("deb","ddd",$a);
        Memcached::value("deb","ddd");
        Memcached::value("deb1","ddd");*/
        $u = null;
        if (isset($_COOKIE["login"]) && isset($_COOKIE["keyconnexion"])) {
            if (extension_loaded("memcached"))
                $u = Memcached::value($_COOKIE["login"], "user");
            if (is_null($u)) {
                $u = \model\mysql\Utilisateur::authentifierUtilisateurParKeyConnexion($_COOKIE["login"], $_COOKIE["keyconnexion"]);
                if ($u)
                    \core\Memcached::value($u->login, "user", $u, 60 * 5);
            } else {
                var_dump($u);
                $u = $u->keyconnexion === $_COOKIE["keyconnexion"] ? $u : false;
            }

        }
        $role = 1;
        $roletext = "Visiteur";
        if ($u && !is_null($u)) {
            $role = Conf::$rolenumero[$u->role];
            $roletext = $u->role;
        }

        Conf::$user["user"] = $u;
        Conf::$user["role"] = $role;
        Conf::$user["roletxt"] = $roletext;
        if ($u) {
            LoaderJavascript::add("base", "controller.setUtilisateur", array(Conf::$user["user"]->login, Conf::$user["user"]->keyconnexion, Conf::$user["user"]->role));
        }
    }

    function error($error)
    {
        header("HTTP/1.0 404 Not Found");
        $controller = new Controller($this->request, $this->debug);
        $controller->set("message", $error);
        $controller->render("/errors/404");
        die();
    }

}