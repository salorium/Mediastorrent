<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 15/05/14
 * Time: 16:32
 */
define('WEBROOT', __DIR__);
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);

function __autoload($class_name)
{
    $filename = ROOT . DS . str_replace("\\", DS, $class_name) . ".php";
    if (file_exists($filename)) {
        require_once $filename;
    } else {

    }

}

//Retour visuel
\config\Conf::$debuglocalfile = false;
\model\simple\Console::println("Debut cron");
$crontache = \model\mysql\Cronroot::getAllNonFini();
foreach ($crontache as $tache) {
    $data = json_decode($tache->donnee, true);
    $cname = $data["classe"];
    $controller = new $cname(null, null);
    if (!in_array($data["fonction"], get_class_methods($controller))) {
        trigger_error("Le controller " . $cname . " n'a pas de méthode " . $data["fonction"]);
        $this->error("Le controller " . $cname . " n'a pas de méthode " . $data["fonction"]);
    }
    $cn = explode("\\", $cname);
    $cn = $cn[count($cn) - 1];
    if ($res = call_user_func_array(array($controller, $data["fonction"]), $data["args"])) {
        //$t->delete();
        $tache->setFini($res);

    }
}
?>