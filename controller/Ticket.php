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
            if (call_user_func_array(array($controller, $data["fonction"]), $data["args"])) {
                $t->delete();
            }
            exit();
        } else {
            $this->set("url", BASE_URL);

        }
    }

    function streaming($id)
    {
        $this->layout = "streaming";
        if ($torrentf = \model\mysql\Torrentfilm::getTorrentFilmParIdForStreaming($id)) {
            \config\Conf::$portscgi = $torrentf->portscgi;
            $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi,
                new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "f.get_frozen_path", array($torrentf->hash, intval($torrentf->numfile))));
            if ($req->success()) {
                $filename = $req->val[0];
                if ($filename == '') {
                    $req = new \model\xmlrpc\rXMLRPCRequest(\config\Conf::$portscgi, array(
                        new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "d.open", $torrentf->hash),
                        new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "f.get_frozen_path", array($torrentf->hash, intval($torrentf->numfile))),
                        new \model\xmlrpc\rXMLRPCCommand(\config\Conf::$portscgi, "d.close", $torrentf->hash)));
                    if ($req->success())
                        $filename = $req->val[1];
                }
                $mediainfo = json_decode($torrentf->mediainfo, true);
                $compfile = "[";
                $compfile .= (strlen($torrentf->complementfichier) > 0 ? $torrentf->complementfichier . "." : "");
                switch ($mediainfo["typequalite"]) {
                    case "SD":
                        $compfile .= $mediainfo["codec"];
                        break;
                    case "HD":
                        $compfile .= $mediainfo["qualite"] . "." . $mediainfo["codec"];
                        break;
                }
                $audios = array();
                foreach ($mediainfo["audios"] as $v) {
                    $res = "";
                    if ($v["type"] !== "MP3") {
                        $res .= $v["type"] . " " . $v["cannal"];
                        if (isset($v["lang"]))
                            $res .= " " . $v["lang"];

                    }
                    $audios[] = $res;
                }
                if (count($audios) > 1) {
                    $au = implode(".", $audios);
                    $compfile .= "." . $au . "]";
                } else {
                    $compfile .= "." . $audios[0] . "]";
                }
                $str = str_replace("'", "\'", str_replace("&lt;", "<", ($torrentf->titre . " " . $compfile . "." . pathinfo($filename, PATHINFO_EXTENSION))));

                $str = htmlentities($str, ENT_NOQUOTES, "UTF-8");

                // remplacer les entités HTML pour avoir juste le premier caractères non accentués
// Exemple : "&ecute;" => "e", "&Ecute;" => "E", "Ã " => "a" ...
                $str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);

// Remplacer les ligatures tel que : Œ, Æ ...
// Exemple "Å“" => "oe"
                $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
// Supprimer tout le reste
                $str = preg_replace('#&[^;]+;#', '', $str);

                $this->set(array(
                    "titre" => $torrentf->titre . " " . $compfile,
                    "src" => "http://" . $torrentf->hostname . "/film/download/" . $id . "/" . \config\Conf::$user["user"]->keyconnexion . "/" . ($str)
                ));
            }
        }
    }

} 