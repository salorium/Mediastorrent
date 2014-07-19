<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 23/03/14
 * Time: 14:15
 */

namespace controller\normal;


use core\Controller;

class Utilisateur extends Controller
{
    function deconnexion()
    {
        //setcookie("login", "", -1, "/");
        setcookie("keyconnexion", "", -1, "/");
        $_COOKIE = null;
        header("Location: " . BASE_URL);
        exit();
    }
} 