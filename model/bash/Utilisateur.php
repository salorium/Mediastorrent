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

        \model\simple\Console::println("Ajout " . $login . " scgi : " . $scgi . (!is_null($taille) ? ' taille ' . $taille . 'Go' : ''));
        //Voir si l'utilisateur existe sur le system
        $sortie = \model\simple\Console::execute("id " . escapeshellarg($login));
        //var_dump($sortie);
        if ($sortie[0] === 1) {
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
                throw new \Exception("Lvm ou le volume groupe " . \config\Conf::$nomvg . " est il bien disponible ?");
            }
            $extends = ((int)$sortie[1]);
            $sortie = \model\simple\Console::execute('vgdisplay -c ' . \config\Conf::$nomvg . ' | awk -F ":" \'{print $13}\'');
            if ($sortie[0] === 1) {
                throw new \Exception("Lvm ou le volume groupe " . \config\Conf::$nomvg . " est il bien disponible ?");
            }
            $tailleextends = ((int)$sortie[1]);
            $free = ($extends * $tailleextends / 1024 / 1024);
            \model\simple\Console::println("Quantité libre " . $free . "Go");
            if ($taille > $free) {
                $taille = $free;
            }
            \model\simple\Console::println("Quantité utilisé " . $taille . "Go par " . $login);
        }

    }
} 