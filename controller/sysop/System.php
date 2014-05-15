<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 06/05/14
 * Time: 09:32
 */

namespace controller\sysop;


class System extends \core\Controller
{
    public $layout = "connecter";

    function addRtorrent()
    {
        if (isset($_REQUEST["nomrtorrent"])) {
            //Traitement de l'ajout
            \model\mysql\Rtorrent::addRtorrentServeur($_REQUEST["nomrtorrent"]);
            $this->set("nonform", true);
        }
        //Affichage du formulaire
    }

    function delRtorrent()
    {
        $this->set("del", \model\mysql\Rtorrent::retirerServeur());
    }
} 