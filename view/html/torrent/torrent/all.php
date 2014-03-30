<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 23/03/14
 * Time: 15:04
 */



\core\LoaderJavascript::add("base","controller.fixeHeightContainer");
\core\LoaderJavascript::add("torrent","controller.init",$seedbox);
?>
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
            <li><a onclick="Torrent.controller.addTorrent.addTorrentShow();" title="Ajouter un Torrent"><img width="40px"   src="<?=BASE_URL?>images/world.svg"/></a></li>
            <li class="divider"></li>
            <li><a onclick="Torrent.controller.startTorrent();" title="Démarrer un Torrent"><img width="40px"   src="<?=BASE_URL?>images/play.svg"/></a></li>
            <li class="divider"></li>
            <li><a onclick="Torrent.controller.pauseTorrent();" title="Mettre en pause un Torrent"><img width="40px"   src="<?=BASE_URL?>images/pause.svg"/></a></li>
            <li class="divider"></li>
            <li><a onclick="Torrent.controller.stopTorrent();" title="Arrêter un Torrent"><img width="40px"   src="<?=BASE_URL?>images/stop.svg"/></a></li>
            <li class="divider"></li>
            <li><a onclick="Torrent.controller.recheckTorrent();" title="Revérification"><img width="40px"   src="<?=BASE_URL?>images/verify.svg"/></a></li>
            <li class="divider"></li>
            <li class="has-dropdown not-click"><a><img width="40px"   src="<?=BASE_URL?>images/poubelle.svg"/></a>

                <ul class="dropdown">
                    <li><a onclick="Torrent.controller.deleteTorrent();">Supprimer</a></li>
                    <li><a onclick="Torrent.controller.deleteAllTorrent();">Supprimer tout</a></li>
                </ul>
            </li>
            <li class="divider"></li>

        </ul>

        <!-- Top Bar Right Nav Elements -->
        <ul class="right">
            <li class="divider hide-for-small"></li>
            <li class="divider"></li>
            <li class="has-form">
                <table id="infoserver" class="infoserver"><tr><td><img width="30px" src="<?=BASE_URL?>images/disk.svg" title="Disque dur"/></td><td><progress title="6.08 Go / 11.05 Go" id="storage" style="width: 150px;" class="diskspace" value="6528540672" max="11873247232"></progress></td></tr></table>

            </li>
            <!-- Divider <img src="images/upload.svg"></td><td>Vitesse <span id="vup">0.1 Ko/s</span> Limite <span id="vupl">2.9 Mo/s</span> Total <span id="vupt">9.22 Ko</span></td><td>|</td><td><img src="images/download.svg"></td><td>Vitesse <span id="vdl">0.1 Ko/s</span> Limite <span id="vdll">Non</span> Total <span id="vdlt">8.31 Ko</span> -->
            <li class="divider"></li>
            <li class="has-form">
                <table id="infoserver" class="infoserver"><tr><td><img width="30px" src="<?=BASE_URL?>images/upload.svg"/></td><td><span id="vup">0.1 Ko/s</span> / <span id="vupl">2.9 Mo/s</span> | <span id="vupt">9.22 Ko</span></td></tr></table>

            </li>
            <li class="divider"></li>
            <li class="has-form">
                <table id="infoserver" class="infoserver"><tr><td><img width="30px" src="<?=BASE_URL?>images/download.svg"/></td><td><span id="vdl">0.1 Ko/s</span> / <span id="vdll">2.9 Mo/s</span> | <span id="vdlt">9.22 Ko</span></td></tr></table>

            </li>

            <!-- Dropdown -->
            </ul>
    </section></nav>
<div id="contenu">
        <div id="moitiegauche" class="large-6 columns panel heightfixed">

            <dl class="sub-nav"> <dt>Tri:</dt> <dd class=""><a onclick="Torrent.controller.tri(this);" sort-colonne="0" >Status <span></span></a></dd><dd><a onclick="Torrent.controller.tri(this);" sort-colonne="1">Nom <span></span></a></dd><dd><a onclick="Torrent.controller.tri(this);" sort-colonne="25">Ajouté <span></span></a></dd></dl>
            <div id="listorrent">
                <fieldset idcpt="0" class="torrent torrentselect" id="0C84BFFEF05094633E6D0668D27B472906449086">
                    <legend><table>
                            <tr><td><svg xml:space="preserve" enable-background="new 0 0 512 512" viewBox="0 0 512 512" height="60px" width="60px" y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" title="Arrêté"><path d="M409.338,166.521c-10.416-54.961-58.666-95.777-115.781-95.777c-35.098,0-67.631,15.285-89.871,41.584c-37.148-9.906-76.079,11.781-86.933,48.779C78.16,172.442,50.6,208.161,50.6,249.569c0,50.852,41.37,92.221,93.222,92.221H369.18c50.85,0,92.221-41.369,92.221-92.221C461.4,213.655,440.941,181.724,409.338,166.521z M369.18,301.79H143.821c-29.795,0-53.222-23.426-53.222-52.221c0-34.078,27.65-60.078,62.186-53.816c-11.536-39.596,44.131-61.93,64.641-32.348c5.157-14.582,25.823-52.662,76.131-52.662c38.027,0,77.361,26.08,78.664,84.982c25.363,0.098,49.18,18.432,49.18,53.844C421.4,278.364,397.975,301.79,369.18,301.79z M278.591,363.455h-45.182v37.802h-33.888v0.463v39.537h112.957V401.72v-0.463h-33.888V363.455z M414.7,401.257h-79.631v40H414.7V401.257z M176.931,401.257H97.3v40h79.631V401.257z" style="fill: gray;"/></svg></td><td>Les.Octonauts.1x42.FRENCH.HDTV.x264-TDPG.mp4</td></tr></tbody></table></legend><table style="width: 100%"><tbody><tr><td width="100%"><progress max="100" value="0" id="p" class="noncomplet"></progress></td><td width="70px" style="text-align:center;min-width:70px; "><span class="pcr">0</span>%</td></tr></tbody></table><table style="width: 100%"><tbody><tr><td width="80px;" style="vertical-align: bottom;">Ajouté</td><td width="170px">: </td><td width="60px;">Seedtime</td><td>: </td><td align="right">Ratio : 0</td></tr><tr><td style="vertical-align: bottom;">Sources</td><td>: 4(0)</td><td>Clients</td><td>: 0(0)</td><td align="right">Upload : - Download : -</td></tr><tr><td style="vertical-align: bottom;">Télécharger</td><td>: 0.00 Ko/73.04 Mo</td><td>Envoyé</td><td>: 0.00 Ko</td><td align="right">Temps restant : ∞</td></tr></tbody></table></fieldset><fieldset idcpt="1" class="torrent " id="844149127AC38370F03C7A0402FCBCE064067B6D"><legend><table><tbody><tr><td><svg xml:space="preserve" enable-background="new 0 0 512 512" viewBox="0 0 512 512" height="60px" width="60px" y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" title="Arrêté"><path d="M409.338,166.521c-10.416-54.961-58.666-95.777-115.781-95.777c-35.098,0-67.631,15.285-89.871,41.584c-37.148-9.906-76.079,11.781-86.933,48.779C78.16,172.442,50.6,208.161,50.6,249.569c0,50.852,41.37,92.221,93.222,92.221H369.18c50.85,0,92.221-41.369,92.221-92.221C461.4,213.655,440.941,181.724,409.338,166.521z M369.18,301.79H143.821c-29.795,0-53.222-23.426-53.222-52.221c0-34.078,27.65-60.078,62.186-53.816c-11.536-39.596,44.131-61.93,64.641-32.348c5.157-14.582,25.823-52.662,76.131-52.662c38.027,0,77.361,26.08,78.664,84.982c25.363,0.098,49.18,18.432,49.18,53.844C421.4,278.364,397.975,301.79,369.18,301.79z M278.591,363.455h-45.182v37.802h-33.888v0.463v39.537h112.957V401.72v-0.463h-33.888V363.455z M414.7,401.257h-79.631v40H414.7V401.257z M176.931,401.257H97.3v40h79.631V401.257z" style="fill: gray;"/></svg></td><td>Les.Octonauts.1x40.FRENCH.HDTV.x264-TDPG.mp4</td></tr></tbody></table></legend><table style="width: 100%"><tbody><tr><td width="100%"><progress max="100" value="0" id="p" class="noncomplet"></progress></td><td width="70px" style="text-align:center;min-width:70px; "><span class="pcr">0</span>%</td></tr></tbody></table><table style="width: 100%"><tbody><tr><td width="80px;" style="vertical-align: bottom;">Ajouté</td><td width="170px">: </td><td width="60px;">Seedtime</td><td>: </td><td align="right">Ratio : 0</td></tr><tr><td style="vertical-align: bottom;">Sources</td><td>: 5(0)</td><td>Clients</td><td>: 0(0)</td><td align="right">Upload : - Download : -</td></tr><tr><td style="vertical-align: bottom;">Télécharger</td><td>: 0.00 Ko/58.72 Mo</td><td>Envoyé</td><td>: 0.00 Ko</td><td align="right">Temps restant : ∞</td></tr></tbody></table></fieldset><fieldset idcpt="2" class="torrent " id="AB28F7819ADEC3C0732B4C4F03442E63D9835066"><legend><table><tbody><tr><td><svg xml:space="preserve" enable-background="new 0 0 512 512" viewBox="0 0 512 512" height="60px" width="60px" y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" title="Arrêté"><path d="M409.338,166.521c-10.416-54.961-58.666-95.777-115.781-95.777c-35.098,0-67.631,15.285-89.871,41.584c-37.148-9.906-76.079,11.781-86.933,48.779C78.16,172.442,50.6,208.161,50.6,249.569c0,50.852,41.37,92.221,93.222,92.221H369.18c50.85,0,92.221-41.369,92.221-92.221C461.4,213.655,440.941,181.724,409.338,166.521z M369.18,301.79H143.821c-29.795,0-53.222-23.426-53.222-52.221c0-34.078,27.65-60.078,62.186-53.816c-11.536-39.596,44.131-61.93,64.641-32.348c5.157-14.582,25.823-52.662,76.131-52.662c38.027,0,77.361,26.08,78.664,84.982c25.363,0.098,49.18,18.432,49.18,53.844C421.4,278.364,397.975,301.79,369.18,301.79z M278.591,363.455h-45.182v37.802h-33.888v0.463v39.537h112.957V401.72v-0.463h-33.888V363.455z M414.7,401.257h-79.631v40H414.7V401.257z M176.931,401.257H97.3v40h79.631V401.257z" style="fill: gray;"/></svg></td><td>Les.Octonauts.1x41.FRENCH.HDTV.x264-TDPG.mp4</td></tr></tbody></table></legend><table style="width: 100%"><tbody><tr><td width="100%"><progress max="100" value="0" id="p" class="noncomplet"></progress></td><td width="70px" style="text-align:center;min-width:70px; "><span class="pcr">0</span>%</td></tr></tbody></table><table style="width: 100%"><tbody><tr><td width="80px;" style="vertical-align: bottom;">Ajouté</td><td width="170px">: </td><td width="60px;">Seedtime</td><td>: </td><td align="right">Ratio : 0</td></tr><tr><td style="vertical-align: bottom;">Sources</td><td>: 4(0)</td><td>Clients</td><td>: 0(0)</td><td align="right">Upload : - Download : -</td></tr><tr><td style="vertical-align: bottom;">Télécharger</td><td>: 0.00 Ko/73.70 Mo</td><td>Envoyé</td><td>: 0.00 Ko</td><td align="right">Temps restant : ∞</td></tr></tbody></table></fieldset><fieldset idcpt="3" class="torrent " id="504A51DBDA63E07FC1E13B9C666900D12087A5E3"><legend><table><tbody><tr><td><svg xml:space="preserve" enable-background="new 0 0 512 512" viewBox="0 0 512 512" height="60px" width="60px" y="0px" x="0px" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" title="Envoi"><path d="m 409.338,216.254 c -10.416,-54.961 -58.666,-95.777 -115.781,-95.777 -35.098,0 -67.631,15.285 -89.871,41.584 -37.148,-9.906 -76.079,11.781 -86.933,48.779 C 78.16,222.176 50.6,257.895 50.6,299.303 c 0,50.852 41.37,92.221 93.222,92.221 l 225.358,0 c 50.85,0 92.221,-41.369 92.221,-92.221 -0.001,-35.914 -20.46,-67.846 -52.063,-83.049 z m -40.158,135.269 -225.359,0 c -29.795,0 -53.222,-23.426 -53.222,-52.221 0,-34.078 27.65,-60.078 62.186,-53.816 -11.536,-39.596 44.131,-61.93 64.641,-32.348 5.157,-14.582 25.823,-52.662 76.131,-52.662 38.027,0 77.361,26.08 78.664,84.982 25.363,0.098 49.18,18.432 49.18,53.844 -0.001,28.796 -23.426,52.221 -52.221,52.221 z m -133.90703,-77.57749 -28.75689,0.11871 59.23404,-59.82071 59.72327,59.32967 -28.75602,0.1187 0.25445,61.64094 -61.44441,0.25364 z" style="fill: #18C72F;"/></svg></td><td>Les.Octonauts.1x44.FRENCH.HDTV.x264-TDPG.mp4</td></tr></tbody></table></legend><table style="width: 100%"><tbody><tr><td width="100%"><progress max="100" value="100" id="p" class="ul"></progress></td><td width="70px" style="text-align:center;min-width:70px; "><span class="pcr">100</span>%</td></tr></tbody></table><table style="width: 100%"><tbody><tr><td width="80px;" style="vertical-align: bottom;">Ajouté</td><td width="170px">: </td><td width="60px;">Seedtime</td><td>: </td><td align="right">Ratio : 0</td></tr><tr><td style="vertical-align: bottom;">Sources</td><td>: 5(0)</td><td>Clients</td><td>: 0(0)</td><td align="right">Upload : - Download : -</td></tr><tr><td style="vertical-align: bottom;">Télécharger</td><td>: 57.09 Mo/57.09 Mo</td><td>Envoyé</td><td>: 0.00 Ko</td><td align="right">Temps restant : ∞</td></tr></tbody></table></fieldset><span onclick="Torrent.next(1)" style="display: block;text-align: center;padding: 2px;">▼</span></div>

        </div>
        <div class="large-6 columns panel heightfixed"><dl class="tabs" data-tab> <dd class="active"><a href="#panel2-1">Détails</a></dd> <dd><a href="#panel2-2">Fichier</a></dd> <dd><a href="#panel2-3">Tracker</a></dd> <dd><a href="#panel2-4">Tab 4</a></dd> </dl> <div class="tabs-content"> <div class="content active" id="panel2-1"> <p>First panel content goes here... Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur enim fuga fugiat laboriosam sapiente voluptate voluptatum. Adipisci animi itaque ullam vitae? Accusantium alias aspernatur, atque dolorum hic odit quas tempore.</p> </div> <div class="content" id="panel2-2"> <p>Second panel content goes here...</p> </div> <div class="content" id="panel2-3"> <p>Third panel content goes here...</p> </div> <div class="content" id="panel2-4"> <p>Fourth panel content goes here...</p> </div> </div></div>
</div>

<div id="addTorrent" class="addTorrent">

    <div id="addTorrentTitle" class="addTorrentTitle"><a><?= preg_replace("#([A-Z]+)#",'<span class="secondary">$1</span>',"Ajouter un torrent");?></a><a class="close" onclick="Torrent.controller.addTorrent.addTorrentHide();">&times;</a></div>
    <div id="addTorrentContenu" class="addTorrentContenu">
        <form id="addtorrent" method="post" enctype="multipart/form-data" onsubmit="Torrent.controller.addTorrent.upload(event);" >
        <div id="baseaddTorrent">
        <div class="row expansion">
                    <div class="small-6 columns">
                        <label for="torrentfile" class="right inline">Torrent</label>
                    </div>
                    <div class="small-6 columns">
                        <input type="file" name="torrentfile" multiple onchange="Torrent.controller.addTorrent.checkTorrentFile($('#mediastorrent').is(':checked'));">
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
                <input class="right" name="mediastorrent" id="mediastorrent" type="checkbox" onchange="Torrent.controller.addTorrent.checkTorrentFile($('#mediastorrent').is(':checked'));">
            </div>
            <div class="small-6 columns">
                <label for="mediastorrent">Ajouter à la bibliothèque</label>
            </div>
        </div>
        </div>
        <div id="addTorrentDetails" class="addTorrentDetails">
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum facilis perferendis possimus rerum
                velit! Adipisci blanditiis delectus deserunt enim exercitationem ipsam iste laudantium libero nesciunt
                repudiandae, rerum sequi vel voluptas?
            </p>
            <p>Aperiam aspernatur doloribus ex, fugit, hic impedit in libero nesciunt nobis perferendis possimus
                provident qui, reiciendis sunt temporibus velit veritatis. A distinctio dolor dolore doloremque fugiat
                odio odit officia voluptate?
            </p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum facilis perferendis possimus rerum
                velit! Adipisci blanditiis delectus deserunt enim exercitationem ipsam iste laudantium libero nesciunt
                repudiandae, rerum sequi vel voluptas?
            </p>
            <p>Aperiam aspernatur doloribus ex, fugit, hic impedit in libero nesciunt nobis perferendis possimus
                provident qui, reiciendis sunt temporibus velit veritatis. A distinctio dolor dolore doloremque fugiat
                odio odit officia voluptate?
            </p><p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum facilis perferendis possimus rerum
                velit! Adipisci blanditiis delectus deserunt enim exercitationem ipsam iste laudantium libero nesciunt
                repudiandae, rerum sequi vel voluptas?
            </p>
            <p>Aperiam aspernatur doloribus ex, fugit, hic impedit in libero nesciunt nobis perferendis possimus
                provident qui, reiciendis sunt temporibus velit veritatis. A distinctio dolor dolore doloremque fugiat
                odio odit officia voluptate?
            </p>
            <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Earum facilis perferendis possimus rerum
                velit! Adipisci blanditiis delectus deserunt enim exercitationem ipsam iste laudantium libero nesciunt
                repudiandae, rerum sequi vel voluptas?
            </p>
            <p>Aperiam aspernatur doloribus ex, fugit, hic impedit in libero nesciunt nobis perferendis possimus
                provident qui, reiciendis sunt temporibus velit veritatis. A distinctio dolor dolore doloremque fugiat
                odio odit officia voluptate?
            </p>

        </div>
        <div id="divbouttonaddtorrent" class="row">
        <div class="small-2 small-centered columns">
        <button class="button small secondary expand" value="ajouter" type="submit" >Ajouter</button>
        </div>
        </div>
    </form>
    </div>
</div>