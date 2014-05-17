<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 17/05/14
 * Time: 09:44
 */

namespace model\bash;


class Utilisateur extends \core\Model
{
    static function addRtorrent($login, $scgi, $taille = null)
    {

        //Voir si l'utilisateur existe sur le system
        $sortie = \model\simple\Console::execute("id " . escapeshellarg($login));
        var_dump($sortie);
        /*if($sortie[0] === 1){
            //Création de l'utilisateur si ce dernier n'existe pas
            $sortie = \model\simple\Console::execute("useradd -m -s /bin/bash ".escapeshellarg($login));
            if ( $sortie[0] === 1){
                throw new \Exception("Impossible de créer l'utilisateur ".$login);
            }
        }
        if (  ! is_null($taille)){
            //Ajout du lvm2
            $sortie = \model\simple\Console::execute('vgdisplay -c ' . \config\Conf::$nomvg . ' | awk -F ":" \'{print $16}\'');
            if ( $sortie[0] === 1){

            }
        }*/

    }
} 