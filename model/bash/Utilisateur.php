<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 17/05/14
 * Time: 09:44
 */

namespace model\bash;


use model\simple\MakerRtorrentLancer;

class Utilisateur extends \core\Model
{
    static function addRtorrent($login, $scgi, $taille = null)
    {

        \model\simple\Console::println("Ajout " . $login . " scgi : " . $scgi . (!is_null($taille) ? ' taille ' . $taille . 'Go' : ''));
        //Voir si l'utilisateur existe sur le system
        $sortie = \model\simple\Console::execute("id " . escapeshellarg($login));

        //var_dump($sortie);
        if ($sortie[0] !== 0) {
            //Création de l'utilisateur si ce dernier n'existe pas
            $sortie = \model\simple\Console::executePath("useradd -m -s /bin/bash " . escapeshellarg($login));
            if ($sortie[0] !== 0) {
                throw new \Exception("Impossible de créer l'utilisateur " . $login);
            }
        }
        if (!is_null($taille) && !is_null(\config\Conf::$nomvg)) {
            //Traitement LVM
            $sortie = \model\simple\Console::executePath('vgdisplay -c ' . \config\Conf::$nomvg . ' | awk -F ":" \'{print $16}\'');
            if ($sortie[0] !== 0) {
                throw new \Exception("Lvm ou le volume groupe " . \config\Conf::$nomvg . " est il bien disponible ?");
            }
            $extends = ((int)$sortie[1]);
            $sortie = \model\simple\Console::executePath('vgdisplay -c ' . \config\Conf::$nomvg . ' | awk -F ":" \'{print $13}\'');
            if ($sortie[0] !== 0) {
                throw new \Exception("Lvm ou le volume groupe " . \config\Conf::$nomvg . " est il bien disponible ?");
            }
            $tailleextends = ((int)$sortie[1]);
            $free = (int)($extends * $tailleextends / 1024 / 1024);
            \model\simple\Console::println("Quantité libre " . $free . "Go");
            if ($taille > $free) {
                $taille = $free;
            }
            \model\simple\Console::println("Quantité utilisé " . $taille . "Go par " . $login);
            $sortie = \model\simple\Console::executePath('lvcreate -n ' . $login . ' -L ' . $taille . 'G ' . \config\Conf::$nomvg);
            if ($sortie[0] !== 0) {
                throw new \Exception("Impossible de créer /dev/" . \config\Conf::$nomvg . "/" . $login);
            }
            $sortie = \model\simple\Console::executePath('mkfs.ext4 /dev/' . \config\Conf::$nomvg . '/' . $login);
            if ($sortie[0] !== 0) {
                throw new \Exception("Erreur lors du formatage /dev/" . \config\Conf::$nomvg . "/" . $login);
            }
            $sortie = \model\simple\Console::execute('mount /dev/' . \config\Conf::$nomvg . '/' . $login . " /home/" . $login);
            if ($sortie[0] !== 0) {
                throw new \Exception("Montage /dev/" . \config\Conf::$nomvg . "/" . $login);
            }
            file_put_contents("/etc/fstab", "\n/dev/" . \config\Conf::$nomvg . "/" . $login . " /home/" . $login . " ext4 defaults,nofail 0 0\n", FILE_APPEND);
        }
        \model\simple\MakerRtorrentConf::create($login, $scgi);
        $sortie = \model\simple\Console::execute('mkdir -p /home/' . $login . '/rtorrent/data');
        if ($sortie[0] !== 0) {
            throw new \Exception("Erreur création du /home/" . $login . "/rtorrent/data");
        }
        $sortie = \model\simple\Console::execute('mkdir -p /home/' . $login . '/rtorrent/session');
        if ($sortie[0] !== 0) {
            throw new \Exception("Erreur création du /home/" . $login . "/rtorrent/session");
        }
        $sortie = \model\simple\Console::execute('chown -R ' . $login . ':' . $login . ' /home/' . $login);
        if ($sortie[0] !== 0) {
            throw new \Exception("Erreur changement de propriétaire /home/" . $login);
        }
        $sortie = MakerRtorrentLancer::start($login);
        if ($sortie[0] !== 0) {
            throw new \Exception("Impossible de lancer rtorrent");
        }
    }

    static function delRtorrent($login)
    {

        \model\simple\Console::println("Del " . $login);
        //Arret de rtorrent
        $sortie = \model\simple\MakerRtorrentLancer::stop($login);
        if ($sortie[0] !== 0) {
            \model\simple\Console::println("Impossible d'arrêté rtorrent");
        }
//Voir l'utilisateur utilise lvm
        if (!is_null(\config\Conf::$nomvg)) {
            $sortie = \model\simple\Console::executePath("lvdisplay /dev/" . \config\Conf::$nomvg . '/' . $login);
            if ($sortie[0] === 0) {
                \model\simple\Console::println("Suppression du lvm en cour");
                //Demontage de l'home de l'utilisateur
                do {
                    $sortie = \model\simple\Console::execute("umount -f /dev/" . \config\Conf::$nomvg . '/' . $login);
                    if ($sortie[0] !== 0) {
                        \model\simple\Console::println("Impossible de démonter /dev/" . \config\Conf::$nomvg . '/' . $login);
                        sleep(10);
                    }
                } while ($sortie[0] !== 0);

                $sortie = \model\simple\Console::executePath("lvremove -f /dev/" . \config\Conf::$nomvg . '/' . $login);
                if ($sortie[0] !== 0) {
                    throw new \Exception("Impossible de supprimer /dev/" . \config\Conf::$nomvg . '/' . $login);
                }
            } else {
                \model\simple\Console::println("Pas de lvm");
            }
        }
        \model\simple\Console::println("Suppression de l'utilisateur");
        $sortie = \model\simple\Console::executePath("userdel -r " . escapeshellarg($login));
        if ($sortie[0] !== 0) {
            throw new \Exception("Impossible de supprimer l'utilisateur " . $login);
        }
    }

    static function rebootRtorrent($login)
    {
        $sortie = MakerRtorrentLancer::stop($login);
        if ($sortie[0] !== 0) {
            throw new \Exception("Impossible d'arrêté rtorrent");
        }
        do {
            $sortie = \model\simple\Console::execute('su ' . escapeshellarg($login) . ' -c "tmux list-sessions"');
            if ($sortie[0] !== 1) {
                \model\simple\Console::println("Rtorrent est encore en exécution");
                sleep(10);
            }

        } while ($sortie[0] !== 1);
        $sortie = MakerRtorrentLancer::start($login);
        if ($sortie[0] !== 0) {
            throw new \Exception("Impossible de lancer rtorrent");
        }
    }
} 