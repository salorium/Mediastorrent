<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 28/03/14
 * Time: 01:58
 */
?>
<nav class="top-bar" data-topbar="">
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
            <li class="has-dropdown not-click" id="seedbox"><a>BouvieurBox1</a><ul class="dropdown"><li><a onclick="Torrent.controller.reloadSeedbox(1)">vcvdv</a></li></ul></li>
            <li class="divider"></li>
            <li><a onclick="Torrent.controller.addTorrentShow();" title="Ajouter un Torrent"><img src="http://mediastorrent/images/world.svg" width="40px"></a></li>
            <li class="divider"></li>
            <li><a onclick="Torrent.controller.startTorrent();" title="Démarrer un Torrent"><img src="http://mediastorrent/images/play.svg" width="40px"></a></li>
            <li class="divider"></li>
            <li><a onclick="Torrent.controller.pauseTorrent();" title="Mettre en pause un Torrent"><img src="http://mediastorrent/images/pause.svg" width="40px"></a></li>
            <li class="divider"></li>
            <li><a onclick="Torrent.controller.stopTorrent();" title="Arrêter un Torrent"><img src="http://mediastorrent/images/stop.svg" width="40px"></a></li>
            <li class="divider"></li>
            <li><a onclick="Torrent.controller.recheckTorrent();" title="Revérification"><img src="http://mediastorrent/images/verify.svg" width="40px"></a></li>
            <li class="divider"></li>
            <li class="has-dropdown not-click"><a><img src="http://mediastorrent/images/poubelle.svg" width="40px"></a>

                <ul class="dropdown"><li class="title back js-generated"><h5><a href="javascript:void(0)">Back</a></h5></li>
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
                <table id="infoserver" class="infoserver"><tbody><tr><td><img src="http://mediastorrent/images/disk.svg" title="Disque dur" width="30px"></td><td><progress title="4.78 Go / 11.05 Go" id="storage" style="width: 150px;" class="diskspace" value="5134737408" max="11873247232"></progress></td></tr></tbody></table>

            </li>
            <!-- Divider <img src="images/upload.svg"></td><td>Vitesse <span id="vup">0.1 Ko/s</span> Limite <span id="vupl">2.9 Mo/s</span> Total <span id="vupt">9.22 Ko</span></td><td>|</td><td><img src="images/download.svg"></td><td>Vitesse <span id="vdl">0.1 Ko/s</span> Limite <span id="vdll">Non</span> Total <span id="vdlt">8.31 Ko</span> -->
            <li class="divider"></li>
            <li class="has-form">
                <table id="infoserver" class="infoserver"><tbody><tr><td><img src="http://mediastorrent/images/upload.svg" width="30px"></td><td><span id="vup"></span> / <span id="vupl">2.9 Mo/s</span> | <span id="vupt">986.02 Ko</span></td></tr></tbody></table>

            </li>
            <li class="divider"></li>
            <li class="has-form">
                <table id="infoserver" class="infoserver"><tbody><tr><td><img src="http://mediastorrent/images/download.svg" width="30px"></td><td><span id="vdl"></span> / <span id="vdll">∞</span> | <span id="vdlt">760.86 Mo</span></td></tr></tbody></table>

            </li>

            <!-- Dropdown -->
        </ul>
    </section></nav>
<div style="height: 830px;" id="contenu">
    <div id="moitiegauche" class="large-6 columns panel heightfixed">

        <dl class="sub-nav"> <dt>Tri:</dt> <dd class=""><a onclick="Torrent.controller.tri(this);" sort-colonne="0">Status <span></span></a></dd><dd class=""><a onclick="Torrent.controller.tri(this);" sort-colonne="1">Nom <span></span></a></dd><dd class="active anc"><a sort-type="-1" onclick="Torrent.controller.tri(this);" sort-colonne="25">Ajouté <span>▼</span></a></dd></dl>
        <div id="listorrent"><span class="bt" onclick="Torrent.controller.next(0)">▲</span><fieldset id="87BDCD8A504BB686405AD8E31FA8227D975E31F5" class="torrent " idcpt="1"><legend><table><tbody><tr><td><svg title="Envoi" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="60px" height="60px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path style="fill: #18C72F;" d="m 409.338,216.254 c -10.416,-54.961 -58.666,-95.777 -115.781,-95.777 -35.098,0 -67.631,15.285 -89.871,41.584 -37.148,-9.906 -76.079,11.781 -86.933,48.779 C 78.16,222.176 50.6,257.895 50.6,299.303 c 0,50.852 41.37,92.221 93.222,92.221 l 225.358,0 c 50.85,0 92.221,-41.369 92.221,-92.221 -0.001,-35.914 -20.46,-67.846 -52.063,-83.049 z m -40.158,135.269 -225.359,0 c -29.795,0 -53.222,-23.426 -53.222,-52.221 0,-34.078 27.65,-60.078 62.186,-53.816 -11.536,-39.596 44.131,-61.93 64.641,-32.348 5.157,-14.582 25.823,-52.662 76.131,-52.662 38.027,0 77.361,26.08 78.664,84.982 25.363,0.098 49.18,18.432 49.18,53.844 -0.001,28.796 -23.426,52.221 -52.221,52.221 z m -133.90703,-77.57749 -28.75689,0.11871 59.23404,-59.82071 59.72327,59.32967 -28.75602,0.1187 0.25445,61.64094 -61.44441,0.25364 z"></path></svg></td><td>Les.Octonauts.1x46.FRENCH.HDTV.x264-TDPG.mp4</td></tr></tbody></table></legend><table style="width: 100%"><tbody><tr><td width="100%"><progress class="ul" id="p" value="100" max="100"></progress></td><td style="text-align:center;min-width:70px; " width="70px"><span class="pcr">100</span>%</td></tr></tbody></table><table style="width: 100%"><tbody><tr><td style="vertical-align: bottom;" width="80px;">Ajouté</td><td width="170px">: 26/03/2014 18:37:20</td><td width="60px;">Seedtime</td><td>: 1j 7h</td><td align="right">Ratio : 0.085</td></tr><tr><td style="vertical-align: bottom;">Sources</td><td>: 6(0)</td><td>Clients</td><td>: 0(0)</td><td align="right">Upload : - Download : -</td></tr><tr><td style="vertical-align: bottom;">Télécharger</td><td>: 64.41 Mo/64.41 Mo</td><td>Envoyé</td><td>: 5.48 Mo</td><td align="right">Temps restant : ∞</td></tr></tbody></table></fieldset><fieldset id="65243664B01A3D6E39B1CAFC66AE1318A8B54964" class="torrent " idcpt="2"><legend><table><tbody><tr><td><svg title="Envoi" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="60px" height="60px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path style="fill: #18C72F;" d="m 409.338,216.254 c -10.416,-54.961 -58.666,-95.777 -115.781,-95.777 -35.098,0 -67.631,15.285 -89.871,41.584 -37.148,-9.906 -76.079,11.781 -86.933,48.779 C 78.16,222.176 50.6,257.895 50.6,299.303 c 0,50.852 41.37,92.221 93.222,92.221 l 225.358,0 c 50.85,0 92.221,-41.369 92.221,-92.221 -0.001,-35.914 -20.46,-67.846 -52.063,-83.049 z m -40.158,135.269 -225.359,0 c -29.795,0 -53.222,-23.426 -53.222,-52.221 0,-34.078 27.65,-60.078 62.186,-53.816 -11.536,-39.596 44.131,-61.93 64.641,-32.348 5.157,-14.582 25.823,-52.662 76.131,-52.662 38.027,0 77.361,26.08 78.664,84.982 25.363,0.098 49.18,18.432 49.18,53.844 -0.001,28.796 -23.426,52.221 -52.221,52.221 z m -133.90703,-77.57749 -28.75689,0.11871 59.23404,-59.82071 59.72327,59.32967 -28.75602,0.1187 0.25445,61.64094 -61.44441,0.25364 z"></path></svg></td><td>Les.Octonauts.1x47.FRENCH.HDTV.x264-TDPG.mp4</td></tr></tbody></table></legend><table style="width: 100%"><tbody><tr><td width="100%"><progress class="ul" id="p" value="100" max="100"></progress></td><td style="text-align:center;min-width:70px; " width="70px"><span class="pcr">100</span>%</td></tr></tbody></table><table style="width: 100%"><tbody><tr><td style="vertical-align: bottom;" width="80px;">Ajouté</td><td width="170px">: 26/03/2014 17:50:50</td><td width="60px;">Seedtime</td><td>: 1j 7h</td><td align="right">Ratio : 1.486</td></tr><tr><td style="vertical-align: bottom;">Sources</td><td>: 6(0)</td><td>Clients</td><td>: 0(0)</td><td align="right">Upload : - Download : -</td></tr><tr><td style="vertical-align: bottom;">Télécharger</td><td>: 61.37 Mo/61.37 Mo</td><td>Envoyé</td><td>: 91.22 Mo</td><td align="right">Temps restant : ∞</td></tr></tbody></table></fieldset><fieldset id="2D0F6190A03608CBA34DB60E611BB13506D448C7" class="torrent " idcpt="3"><legend><table><tbody><tr><td><svg title="Envoi" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="60px" height="60px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path style="fill: #18C72F;" d="m 409.338,216.254 c -10.416,-54.961 -58.666,-95.777 -115.781,-95.777 -35.098,0 -67.631,15.285 -89.871,41.584 -37.148,-9.906 -76.079,11.781 -86.933,48.779 C 78.16,222.176 50.6,257.895 50.6,299.303 c 0,50.852 41.37,92.221 93.222,92.221 l 225.358,0 c 50.85,0 92.221,-41.369 92.221,-92.221 -0.001,-35.914 -20.46,-67.846 -52.063,-83.049 z m -40.158,135.269 -225.359,0 c -29.795,0 -53.222,-23.426 -53.222,-52.221 0,-34.078 27.65,-60.078 62.186,-53.816 -11.536,-39.596 44.131,-61.93 64.641,-32.348 5.157,-14.582 25.823,-52.662 76.131,-52.662 38.027,0 77.361,26.08 78.664,84.982 25.363,0.098 49.18,18.432 49.18,53.844 -0.001,28.796 -23.426,52.221 -52.221,52.221 z m -133.90703,-77.57749 -28.75689,0.11871 59.23404,-59.82071 59.72327,59.32967 -28.75602,0.1187 0.25445,61.64094 -61.44441,0.25364 z"></path></svg></td><td>Les.Octonauts.1x48.FRENCH.HDTV.x264-TDPG.mp4</td></tr></tbody></table></legend><table style="width: 100%"><tbody><tr><td width="100%"><progress class="ul" id="p" value="100" max="100"></progress></td><td style="text-align:center;min-width:70px; " width="70px"><span class="pcr">100</span>%</td></tr></tbody></table><table style="width: 100%"><tbody><tr><td style="vertical-align: bottom;" width="80px;">Ajouté</td><td width="170px">: </td><td width="60px;">Seedtime</td><td>: </td><td align="right">Ratio : 0</td></tr><tr><td style="vertical-align: bottom;">Sources</td><td>: 5(0)</td><td>Clients</td><td>: 0(0)</td><td align="right">Upload : - Download : -</td></tr><tr><td style="vertical-align: bottom;">Télécharger</td><td>: 63.57 Mo/63.57 Mo</td><td>Envoyé</td><td>: 0.00 Ko</td><td align="right">Temps restant : ∞</td></tr></tbody></table></fieldset><fieldset id="CC7AC0712261FF4055FF8FD167970C1B45B76128" class="torrent " idcpt="4"><legend><table><tbody><tr><td><svg title="Envoi" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="60px" height="60px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path style="fill: #18C72F;" d="m 409.338,216.254 c -10.416,-54.961 -58.666,-95.777 -115.781,-95.777 -35.098,0 -67.631,15.285 -89.871,41.584 -37.148,-9.906 -76.079,11.781 -86.933,48.779 C 78.16,222.176 50.6,257.895 50.6,299.303 c 0,50.852 41.37,92.221 93.222,92.221 l 225.358,0 c 50.85,0 92.221,-41.369 92.221,-92.221 -0.001,-35.914 -20.46,-67.846 -52.063,-83.049 z m -40.158,135.269 -225.359,0 c -29.795,0 -53.222,-23.426 -53.222,-52.221 0,-34.078 27.65,-60.078 62.186,-53.816 -11.536,-39.596 44.131,-61.93 64.641,-32.348 5.157,-14.582 25.823,-52.662 76.131,-52.662 38.027,0 77.361,26.08 78.664,84.982 25.363,0.098 49.18,18.432 49.18,53.844 -0.001,28.796 -23.426,52.221 -52.221,52.221 z m -133.90703,-77.57749 -28.75689,0.11871 59.23404,-59.82071 59.72327,59.32967 -28.75602,0.1187 0.25445,61.64094 -61.44441,0.25364 z"></path></svg></td><td>Max.Steel.2013.S01E14.FRENCH.WEBDL.XviD-MDR.avi</td></tr></tbody></table></legend><table style="width: 100%"><tbody><tr><td width="100%"><progress class="ul" id="p" value="100" max="100"></progress></td><td style="text-align:center;min-width:70px; " width="70px"><span class="pcr">100</span>%</td></tr></tbody></table><table style="width: 100%"><tbody><tr><td style="vertical-align: bottom;" width="80px;">Ajouté</td><td width="170px">: </td><td width="60px;">Seedtime</td><td>: </td><td align="right">Ratio : 0</td></tr><tr><td style="vertical-align: bottom;">Sources</td><td>: 3(0)</td><td>Clients</td><td>: 0(0)</td><td align="right">Upload : - Download : -</td></tr><tr><td style="vertical-align: bottom;">Télécharger</td><td>: 202.32 Mo/202.32 Mo</td><td>Envoyé</td><td>: 0.00 Ko</td><td align="right">Temps restant : ∞</td></tr></tbody></table></fieldset><span class="bt" onclick="Torrent.controller.next(2)">▼</span></div>

    </div>
    <div class="large-6 columns panel heightfixed"><dl class="tabs" data-tab=""> <dd class="active"><a href="#panel2-1">Détails</a></dd> <dd class=""><a href="#panel2-2">Fichier</a></dd> <dd class=""><a href="#panel2-3">Tracker</a></dd> <dd class=""><a href="#panel2-4">Tab 4</a></dd> </dl> <div class="tabs-content"> <div class="content active" id="panel2-1"> <p>First panel content goes here... Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consequuntur enim fuga fugiat laboriosam sapiente voluptate voluptatum. Adipisci animi itaque ullam vitae? Accusantium alias aspernatur, atque dolorum hic odit quas tempore.</p> </div> <div class="content" id="panel2-2"> <p>Second panel content goes here...</p> </div> <div class="content" id="panel2-3"> <p>Third panel content goes here...</p> </div> <div class="content" id="panel2-4"> <p>Fourth panel content goes here...</p> </div> </div></div>
