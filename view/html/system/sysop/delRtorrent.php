<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 06/05/14
 * Time: 18:02
 */
if ($del) {
    \core\LoaderJavascript::add("base", "controller.redirection", \core\Router::url(""));
    ?>
    <div class="container">
        <div data-alert class="alert-box success radius connexion">
            Les serveur à bien été supprimé de la liste des serveurs rtorrent.
            <a href="#" class="close">&times;</a>
        </div>
    </div>
<?
} else {
    ?>
    <div class="container">
        <div data-alert class="alert-box alert radius connexion">
            Erreur lors de la suppression du serveur de seebox
            <a href="#" class="close">&times;</a>
        </div>
    </div>
<?
}
?>