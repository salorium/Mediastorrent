<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 06/05/14
 * Time: 18:21
 */
\core\LoaderJavascript::add("base", "controller.fixeHeightContainer");
\core\LoaderJavascript::add("base", "controller.setHost", array(substr($_SERVER["HTTP_HOST"] . dirname(dirname($_SERVER["SCRIPT_NAME"])) . ($_SERVER["SCRIPT_NAME"] !== "/index.php" ? "/" : ""), 0, -1), $_SERVER["SERVER_PORT"] == 443));

\core\LoaderJavascript::add("sysoputilisateur", "controller.init");
\core\LoaderJavascript::add("sysoputilisateur", "controller.setRole", array_slice(\config\Conf::$numerorole, 2));
?>
<div class="container">
<nav class="top-bar" data-topbar>
    <!-- Title -->
    <ul class="title-area">
        <li class="name"></li>

        <!-- Mobile Menu Toggle -->
        <li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
    </ul>

    <!-- Top Bar Section -->
    <!--</a><a href="#ADD"><img width="40px"  title="Démarrer un Torrent" src="images/play.svg"/></a><a href="#ADD"><img width="40px"  title="Mettre en pause un Torrent" src="images/pause.svg"/></a><a href="#ADD"><img width="40px"  title="Arrêter un Torrent" src="images/stop.svg"/></a>-->
    <section class="top-bar-section">
        <!--<img src="images/disk.svg" title="Disque dur">
        <!-- Top Bar Left Nav Elements -->
        <ul class="left">
            <li class="divider"></li>
            <li><a onclick="Sysoputilisateur.view.ajouteUtilisateur();">Ajouter un utilisateur</a>
            </li>
            <li class="divider"></li>
            <li id="utilisateur" class="has-dropdown not-click">

                <a><?= $user->login ?></a>
                <ul class="dropdown">
                    <?
                    foreach ($users as $v) {
                        if ($v->login !== $user->login) {
                            ?>
                            <li><a onclick="Sysoputilisateur.controller.updateUser(this);"><?= $v->login; ?></a></li>
                        <?
                        }
                    }
                    ?>
                </ul>
            </li>
            <li class="divider"></li>
            <li><a onclick="Sysoputilisateur.controller.delUser()"><img width="40px"
                                                                        src="<?= BASE_URL ?>images/poubelle.svg"/></a>
            </li>
            <li class="divider"></li>

        </ul>

        <!-- Top Bar Right Nav Elements -->
        <ul class="right">
            <!-- Dropdown -->
        </ul>
    </section>
</nav>

<div id="contenu">
<div id="souscontenu" class="heightfixed">
<div id="moitiegauche" class="large-5 columns panel heightfixed">
    <form id="updateuser" method="post">
        <input type="hidden" value="updateuser" name="action">
    </form>
    <form id="deluser" method="post">
        <input type="hidden" value="deluser" name="action">
        <input type="hidden" name="login" value="<?= $user->login; ?>"/>
    </form>
    <input type="hidden" id="login" value="<?= $user->login; ?>"/>

    <form data-abide method="post">
        <input type="hidden" name="login" value="<?= $user->login; ?>"/>
        <input type="hidden" value="modifierpassword" name="action">
        <fieldset>
            <legend>Changer le mot de passe</legend>
            <div class="row">
                <div class="large-8 columns"><input type="password" required pattern="password"/>
                    <small class="error">Le mot de passe est obligatoire ! (Au moins 8 caractères avec une
                        lettre
                        majuscule, un chiffre / un caractère spécial.)
                    </small>
                </div>
                <div class="large-4 columns">
                    <button type="submit" class="secondary tiny">Modifier</button>
                </div>
            </div>

        </fieldset>
    </form>

    <form data-abide method="post">
        <input type="hidden" name="login" value="<?= $user->login; ?>"/>
        <input type="hidden" value="changerrole" name="action">
        <fieldset>
            <legend>Changer le rôle</legend>
            <div class="row">
                <div class="large-8 columns">
                    <select name="role" data-invalid="" id="customDropdown2" class="medium" required="">
                        <?
                        foreach ($role as $k => $rt) {
                            ?>
                            <option
                                value="<?= $rt; ?>" <?= ($user->role === $rt ? 'selected="selected" ' : ""); ?>><?= $rt; ?></option>
                        <?
                        }
                        ?>

                    </select>
                    <small class="error">Sélectionner un rôle.</small>
                </div>
                <div class="large-4 columns">
                    <button type="submit" class="secondary tiny">Modifier</button>
                </div>
            </div>

        </fieldset>
    </form>


    <? if (count($rtorrents) > 0) { ?>
        <form data-abide method="post">
            <input type="hidden" name="login" value="<?= $user->login; ?>"/>
            <fieldset>
                <legend>Ajouter un serveur rtorrent</legend>
                <label for="customDropdown1">Sélection du serveur rtorrent
                    <small>obligatoire</small>
                    <select name="nomrtorrent" data-invalid="" id="customDropdown1" class="medium" required="">
                        <option value="">Sélection du serveur rtorrent</option>
                        <?
                        foreach ($rtorrents as $k => $rt) {
                            ?>
                            <option value="<?= $k; ?>"><?= $k; ?> (<?= $rt["host"]; ?>)
                                <?
                                if (count($rt["scgi"]) > 0)
                                    echo " [" . implode(", ", $rt["scgi"]) . "]";
                                ?>
                            </option>
                        <?
                        }
                        ?>

                    </select>
                </label>
                <small class="error">Sélectionner un serveur rtorrent.</small>
                <label>Port SCGI :
                    <small>obligatoire</small>
                    <input type="text" required pattern="[0-9]{4}" name="scgi"/>
                    <small class="error">Le ports scgi est obligatoire, un nombre à 4 chiffres!
                    </small>
                </label>
                <label>Taille du répertoire en Go si le serveur de rtorrent est configuré en lvm2:
                    <input type="text" pattern="[0-9]+" name="taille"/>
                    <small class="error">Merci d'entrer la taille du repertoire de l'utilisateur!
                    </small>
                </label>
                <button type="submit" class="secondary tiny small-3">Ajouter</button>
            </fieldset>
            <input type="hidden" value="addrtorrent" name="action">
        </form>
    <? } ?>
</div>
<div id="moitiedroite" class="large-7 columns panel heightfixed">
    <dl class="tabs" data-tab>
        <dd id="btdetails" class="active"><a href="#panel2-1">Détails</a></dd>
        <dd><a href="#panel2-2">Fichier</a></dd>
        <dd><a class="disabled" href="#panel2-3">Tracker</a></dd>
        <dd><a href="#panel2-4">Tab 4</a></dd>
    </dl>
    <div class="tabs-content">
        <div class="content active" id="panel2-1">
            <fieldset>
                <legend>Général</legend>
                <table class="noneventrowbg" style="width: 100%;border: none;">
                    <tr>
                        <td>Nom:</td>
                        <td id="torrentdetailnom"></td>
                    </tr>
                    <tr>
                        <td>Date:</td>
                        <td id="torrentdetaildateaj"></td>
                    </tr>
                    <tr>
                        <td>Répertoire:</td>
                        <td id="torrentdetailrep"></td>
                    </tr>
                    <tr>
                        <td>Hash:</td>
                        <td id="torrentdetailhash"></td>
                    </tr>
                    <tr>
                        <td>Infos:</td>
                        <td id="torrentdetailinfos" class="red"></td>
                    </tr>
                </table>
            </fieldset>
            <fieldset>
                <legend>Transfert</legend>
                <table class="noneventrowbg" style="width: 100%;border: none;">
                    <tr>
                        <td>Temps écoulé:</td>
                        <td id="torrentdetailtempsecoule"></td>
                        <td>Restant:</td>
                        <td id="torrentdetailrestant"></td>
                        <td>Ratio:</td>
                        <td id="torrentdetailratio"></td>
                    </tr>
                    <tr>
                        <td>Téléchargé:</td>
                        <td id="torrentdetaildl"></td>
                        <td>Vitesse de réception:</td>
                        <td id="torrentdetailvdl"></td>
                        <td>Rejeté:</td>
                        <td id="torrentdetailrejete"></td>
                    </tr>
                    <tr>
                        <td>Envoyé:</td>
                        <td id="torrentdetailul"></td>
                        <td>Vitesse de d'émission:</td>
                        <td id="torrentdetailvul"></td>
                    </tr>
                    <tr>
                        <td>Sources:</td>
                        <td id="torrentdetailsource"></td>
                        <td>Clients:</td>
                        <td id="torrentdetailclient"></td>
                    </tr>
                </table>
            </fieldset>

        </div>
        <div class="content" id="panel2-2">
            <!--<table class="scroll">
                <thead style="width: inherit;"><tr><th style='width: 60%;'>Nom</th><th style='width: 20%;'>Taille</th><th style='width: 20%;'>Reçus</th><th style='width: 20%;'>%</th><th style='width: 20%;'>Priorité</th></tr></thead>
                <tbody id="torrentdetailsfiles">

                </tbody>
            </table>-->
            <div style="overflow-y: auto;position: relative;height: inherit;">
                <table style="width: 100%;">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Taille</th>
                        <th>Reçus</th>
                        <th style='width: 150px;'>%</th>
                        <th>Priorité</th>
                    </tr>
                    </thead>
                    <tbody id="torrentdetailsfiles">
                    </tbody>
                </table>
                <iframe name='datafrm' id='datafrm'
                        style="height: 0px;width: 0px;border: none;display: none;"></iframe>
                <form action="" id="getdata" method="get" target="datafrm">
                </form>
            </div>
        </div>
        <div class="content" id="panel2-3">
            <div style="overflow-y: auto;position: relative;height: inherit;">
                <table style="width: 100%;">
                    <thead>
                    <tr>
                        <th style='width: 200px;'>Nom</th>
                        <th>Type</th>
                        <th>Activé</th>
                        <th>Groupe</th>
                        <th>Sources</th>
                        <th>Clients</th>
                        <th>Téléchargé</th>
                        <th>A été mis à jour</th>
                        <th>Intervalle</th>
                        <th>Privé</th>
                    </tr>
                    </thead>
                    <tbody id="torrentdetailstrackers">
                    </tbody>
                </table>
            </div>

        </div>
        <div class="content" id="panel2-4"><p>Fourth panel content goes here...</p></div>
    </div>
</div>
</div>
</div>
</div>