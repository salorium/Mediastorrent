<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 12/03/14
 * Time: 21:37
 */

namespace controller;


class Ticket extends \core\Controller
{
    function traite($id)
    {
        $t = \model\mysql\Ticket::traiteTicket($id);
        if ($t) {

            $data = json_decode($t->donnee, true);
            $cname = $data["classe"];
            $controller = new $cname($this->request, $this->debug);
            if (!in_array($data["fonction"], get_class_methods($controller))) {
                trigger_error("Le controller " . $cname . " n'a pas de méthode " . $data["fonction"]);
                $this->error("Le controller " . $cname . " n'a pas de méthode " . $data["fonction"]);
            }
            $cn = explode("\\", $cname);
            $cn = $cn[count($cn) - 1];
            $this->request->controller = strtolower($cn);
            $this->request->action = $data["fonction"];
            if (call_user_func_array(array($controller, $data["fonction"]), $data["args"])) {
                $t->delete();
            }
            $controller->render($this->request->action);
            exit();
        } else {
            $this->set("url", BASE_URL);

        }
    }
} 