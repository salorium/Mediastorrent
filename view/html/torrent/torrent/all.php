<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 23/03/14
 * Time: 15:04
 */


\core\LoaderJavascript::add("base", "controller.fixeHeightContainer");
\core\LoaderJavascript::add("base", "controller.tableScroll");
\core\LoaderJavascript::add("torrent1", "controller.init", $seedbox);
\core\LoaderJavascript::add("base", "controller.setHost", array($_SERVER["HTTP_HOST"] . dirname(dirname($_SERVER["SCRIPT_NAME"])) . ($_SERVER["SCRIPT_NAME"] !== "/index.php" ? "/" : ""), $_SERVER["SERVER_PORT"] == 443))

?>
<script>
    document.oncontextmenu = new Function("return false");
</script>
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
            <li id="seedbox"><a>Rtorrent</a>

                <ul class="dropdown">
                    <li><a href="#">Dance</a></li>
                    <li><a href="#">Hip-hop</a></li>
                </ul>
            </li>
            <li class="divider"></li>
            <li><a onclick="Torrent1.controller.createTorrent.show();" title="Créer un Torrent"><img width="40px"
                                                                                                     src="<?= BASE_URL ?>images/star.svg"/></a>
            </li>
            <li class="divider"></li>
            <li><a onclick="Torrent1.controller.addTorrent.show();" title="Ajouter un Torrent"><img width="40px"
                                                                                                    src="<?= BASE_URL ?>images/world.svg"/></a>
            </li>
            <li class="divider"></li>
            <li><a onclick="Torrent1.controller.listTorrent.start();" title="Démarrer un Torrent"><img width="40px"
                                                                                                       src="<?= BASE_URL ?>images/play.svg"/></a>
            </li>
            <li class="divider"></li>
            <li><a onclick="Torrent1.controller.listTorrent.pause();" title="Mettre en pause un Torrent"><img
                        width="40px" src="<?= BASE_URL ?>images/pause.svg"/></a></li>
            <li class="divider"></li>
            <li><a onclick="Torrent1.controller.listTorrent.stop();" title="Arrêter un Torrent"><img width="40px"
                                                                                                     src="<?= BASE_URL ?>images/stop.svg"/></a>
            </li>
            <li class="divider"></li>
            <li><a onclick="Torrent1.controller.listTorrent.recheck();" title="Revérification"><img width="40px"
                                                                                                    src="<?= BASE_URL ?>images/verify.svg"/></a>
            </li>
            <li class="divider"></li>
            <li class="has-dropdown not-click"><a><img width="40px" src="<?= BASE_URL ?>images/poubelle.svg"/></a>

                <ul class="dropdown">
                    <li><a onclick="Torrent1.controller.listTorrent.delete();">Supprimer</a></li>
                    <li><a onclick="Torrent1.controller.listTorrent.deleteAll();">Supprimer les données</a></li>
                </ul>
            </li>
            <li class="divider"></li>

        </ul>

        <!-- Top Bar Right Nav Elements -->
        <ul class="right">
            <li class="divider hide-for-small"></li>
            <li class="divider"></li>
            <li class="has-form">
                <table id="infoserver" class="infoserver">
                    <tr>
                        <td><img width="30px" src="<?= BASE_URL ?>images/disk.svg" title="Disque dur"/></td>
                        <td>
                            <progress title="6.08 Go / 11.05 Go" id="storage" style="width: 150px;" class="diskspace"
                                      value="6528540672" max="11873247232"></progress>
                        </td>
                    </tr>
                </table>

            </li>
            <!-- Divider <img src="images/upload.svg"></td><td>Vitesse <span id="vup">0.1 Ko/s</span> Limite <span id="vupl">2.9 Mo/s</span> Total <span id="vupt">9.22 Ko</span></td><td>|</td><td><img src="images/download.svg"></td><td>Vitesse <span id="vdl">0.1 Ko/s</span> Limite <span id="vdll">Non</span> Total <span id="vdlt">8.31 Ko</span> -->
            <li class="divider"></li>
            <li class="has-form">
                <table id="infoserver" class="infoserver">
                    <tr>
                        <td><img width="30px" src="<?= BASE_URL ?>images/upload.svg"/></td>
                        <td><span id="vup">0.1 Ko/s</span> / <span id="vupl">2.9 Mo/s</span> | <span
                                id="vupt">9.22 Ko</span></td>
                    </tr>
                </table>

            </li>
            <li class="divider"></li>
            <li class="has-form">
                <table id="infoserver" class="infoserver">
                    <tr>
                        <td><img width="30px" src="<?= BASE_URL ?>images/download.svg"/></td>
                        <td><span id="vdl">0.1 Ko/s</span> / <span id="vdll">2.9 Mo/s</span> | <span
                                id="vdlt">9.22 Ko</span></td>
                    </tr>
                </table>

            </li>

            <!-- Dropdown -->
        </ul>
    </section>
</nav>
<div id="contenu">
    <div id="souscontenu" class="heightfixed">
        <div id="moitiegauche" class="large-5 columns panel heightfixed">

            <dl class="sub-nav">
                <dt>Tri:</dt>
                <dd class=""><a onclick="Torrent1.controller.listTorrent.tri(this);" sort-colonne="0">Status
                        <span></span></a></dd>
                <dd><a onclick="Torrent1.controller.listTorrent.tri(this);" sort-colonne="1">Nom <span></span></a></dd>
                <dd><a onclick="Torrent1.controller.listTorrent.tri(this);" sort-colonne="25">Ajouté <span></span></a>
                </dd>
                <dd><a onclick="Torrent1.controller.listTorrent.tri(this);" sort-colonne="6">Ratio <span></span></a>
                </dd>
            </dl>
            <div id="listorrent">
            </div>

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

<div id="addTorrent" class="addTorrent">

    <div id="addTorrentTitle" class="addTorrentTitle">
        <a><?= preg_replace("#([A-Z]+)#", '<span class="secondary">$1</span>', "Ajouter un torrent"); ?></a><a
            class="close" onclick="Torrent1.controller.addTorrent.hide();">&times;</a></div>
    <div id="addTorrentContenu" class="addTorrentContenu">
        <form id="addtorrent" method="post" enctype="multipart/form-data"
              onsubmit="Torrent1.controller.addTorrent.upload(event);">
            <div id="baseaddTorrent">
                <div class="row expansion">
                    <div class="small-6 columns">
                        <label for="torrentfile" class="right inline">Torrent</label>
                    </div>
                    <div class="small-6 columns">
                        <input type="file" name="torrentfile[]" multiple
                               onchange="Torrent1.controller.addTorrent.files.check($('#mediastorrent').is(':checked'));">
                    </div>
                </div>
                <div class="row expansion">
                    <div class="small-6 columns">
                        <input class="right" name="autostart" id="autostart" type="checkbox">
                    </div>
                    <div class="small-6 columns">
                        <label for="autostart">Ne pas démarrer le téléchargement</label>
                    </div>
                </div>
                <div class="row expansion">
                    <div class="small-6 columns">
                        <input class="right" name="mediastorrent" id="mediastorrent" type="checkbox"
                               onchange="Torrent1.controller.addTorrent.files.check($('#mediastorrent').is(':checked'));">
                    </div>
                    <div class="small-6 columns">
                        <label for="mediastorrent">Ajouter à la bibliothèque</label>
                    </div>
                </div>
            </div>
            <center>
                <div id="addTorrentDetails" class="addTorrentDetails">

                </div>
            </center>
            <div id="divbouttonaddtorrent" class="row">
                <div class="small-2 small-centered columns">
                    <button class="button small secondary expand" value="ajouter" type="submit">Ajouter</button>
                </div>
            </div>
        </form>
    </div>
</div>
<div id="createTorrent" class="addTorrent">

    <div id="createTorrentTitle" class="addTorrentTitle">
        <a><?= preg_replace("#([A-Z]+)#", '<span class="secondary">$1</span>', "Créer un torrent"); ?></a><a
            class="close" onclick="Torrent1.controller.createTorrent.hide();">&times;</a></div>
    <div id="createTorrentContenu" class="addTorrentContenu">
        <form id="createtorrent" method="post" enctype="multipart/form-data"
              onsubmit="Torrent1.controller.addTorrent.upload(event);">

            <div id="divrepcreatetorrent" class="row">
                <fieldset>
                    <legend>Répertoire</legend>
                    <div class="row expansion">
                        <div class="large-10 columns large-centered">
                            <input type="text" id="repertoire" name="repertoire" value="/home/salorium/rtorrent/data"
                                   readonly>
                        </div>
                    </div>
                    <div id="folder" style="overflow-y: auto;overflow-x: hidden;" class="row expansion">

                    </div>
                </fieldset>
            </div>

            <div id="divpropcreatetorrent" class="row">
                <fieldset>
                    <legend>Propriétés du torrent</legend>
                    <div class="row expansion">
                        <div class="large-6 columns">
                            <label for="trackers" class="text-center inline">Trackers : </label>
                        </div>
                        <div class="large-6 columns">
                            <textarea name="trackers" id="trackers"></textarea>
                        </div>
                    </div>
                    <div class="row expansion">
                        <div class="large-6 columns">
                            <label for="piece" class="text-center inline">Pièces : </label>
                        </div>
                        <div class="large-6 columns">
                            <select name="piece" id="piece">
                                <option value="32">32 Ko</option>
                                <option value="64">64 Ko</option>
                                <option value="128">128 Ko</option>
                                <option value="256" selected="selected">256 Ko</option>
                                <option value="512">512 Ko</option>
                                <option value="1024">1 Mo</option>
                                <option value="2048">2 Mo</option>
                                <option value="4096">4 Mo</option>
                                <option value="8192">8 Mo</option>
                                <option value="16384">16 Mo</option>
                            </select>
                        </div>
                    </div>
                    <div class="row expansion">
                        <div class="large-1 columns">
                            <input name="seed" id="seed" type="checkbox">
                        </div>
                        <div class="large-5 columns">
                            <label for="seed">Mettre en seed</label>
                        </div>
                        <div class="large-1 columns">
                            <input name="private" id="private" type="checkbox">
                        </div>
                        <div class="large-5 columns">
                            <label for="private">Tracker privé</label>
                        </div>
                    </div>
                </fieldset>
            </div>

            <div id="divbouttoncreatetorrent" class="row">
                <div class="small-2 small-centered columns">
                    <button class="button small secondary expand" value="create" type="submit">Créer</button>
                </div>
            </div>
        </form>
    </div>
    <div id="createToto" style="display: none;">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ab animi cum
        dolor earum eligendi facilis illum incidunt laudantium non numquam odio omnis perspiciatis, porro praesentium,
        quisquam repellat repudiandae sunt voluptatem!
    </div>
</div>
<!--<div id="cliquedroit" style="background-color: darkslategray;width: 150px;display: none;"></div>
</div>-->