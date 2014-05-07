<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 06/05/14
 * Time: 09:36
 */
if (isset($nonform)) {
    if ($nonform) {
        ?>
        <div class="container">
            <div data-alert class="alert-box success radius connexion">
                Enregistrement du serveur de seebox faite !
                <a href="#" class="close">&times;</a>
            </div>
        </div>
    <?
    } else {
        ?>
        <div class="container">
            <div data-alert class="alert-box alert radius connexion">
                Erreur lors du enregistrement du serveur de seebox
                <a href="#" class="close">&times;</a>
            </div>
        </div>
    <?
    }
} else {
    ?>


    <div class="column large-centered large-3">

        <form method="post">
            <fieldset>
                <legend>Seveur de torrent</legend>
                <div class="row">
                    <div class="large-4">Nom du serveur</div>
                    <div class="large-8"><input type="text" name="nomrtorrent"/></div>
                </div>
                <button class="button small secondary" value="Connexion" type="submit">Ajouter ce serveur en tant que
                    serveur rtorrent
                </button>
            </fieldset>

        </form>
    </div>
    </div>
<? } ?>