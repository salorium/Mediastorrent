<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 12/03/14
 * Time: 20:31
 */

namespace model\simple;


class ChaineCaractere extends \core\Model
{
    static function random($car, $espace = false)
    {
        $string = "";
        $chaine = "abcdefghijklmnpqrstuvwxyAZERTYUIOPQSDFGHJKLMWXCVBN0123456789" . ($espace ? " " : "");
        srand((double)microtime() * 1000000);
        for ($i = 0; $i < $car; $i++) {
            $string .= $chaine[rand() % strlen($chaine)];
        }
        return $string;
    }

    static function styleString($str)
    {
        return preg_replace("#([A-Z]+)#", '<span class="secondary">$1</span>', $str);
    }

    static function styleError($str)
    {
        return preg_replace("#(.+)#", '<span style="color:red;">$1</span>', $str);
    }

    static function styleSuccess($str)
    {
        return preg_replace("#(.+)#", '<span style="color:green;">$1</span>', $str);
    }

    static function remplaceAccent($str)
    {
        $str = htmlentities($str, ENT_NOQUOTES, "UTF-8");

        // remplacer les entités HTML pour avoir juste le premier caractères non accentués
// Exemple : "&ecute;" => "e", "&Ecute;" => "E", "Ã " => "a" ...
        $str = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $str);

// Remplacer les ligatures tel que : Œ, Æ ...
// Exemple "Å“" => "oe"
        $str = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $str);
// Supprimer tout le reste
        $str = preg_replace('#&[^;]+;#', '', $str);
        return $str;
    }
} 