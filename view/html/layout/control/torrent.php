<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 20/03/14
 * Time: 15:53
 */
?>

<nav class="top-bar" data-topbar="">
    <!-- Title -->
    <ul class="title-area">
        <li class="name"><h1><a
                    href="#"><?= \config\Conf::$user["user"]->login . " (" . \config\Conf::$user["user"]->role . ")"; ?></a>
            </h1></li>

        <!-- Mobile Menu Toggle -->
        <li class="toggle-topbar menu-icon"><a href="#">Menu</a></li>
    </ul>

    <!-- Top Bar Section -->

    <section class="top-bar-section">

        <!-- Top Bar Left Nav Elements -->
        <ul class="left">
            <li class="divider"></li>
            <li class=""><a href="<?= \core\Router::url("torrent/all") ?>"><img width="30px" title="Torrent"
                                                                                src="<?= BASE_URL ?>images/torrent.svg?color=rgba(240,240,240,1)"/></a>
            </li>
            <li class="divider"></li>
            <!-- Search | has-form wrapper -->
            <li class="has-dropdown not-click"><a href="<?= \core\Router::url("film/nouveau") ?>"><img width="30px"
                                                                                                       title="Film"
                                                                                                       src="<?= BASE_URL ?>images/film.svg?color=rgba(240,240,240,1)"/></a>

                <ul class="dropdown">
                    <li class="has-dropdown"><a>Nouveauté</a>

                        <ul class="dropdown">
                            <li><a href="?controller=Film&fonction=nouveau">Nouveauté</a></li>
                            <li class="divider"></li>
                            <li><label>Genre</label></li>
                            <li><a href="#">Annimation</a></li>
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Horreur</a></li>
                            <li><a href="#">Thriller</a></li>
                            <li><a href="#">Comédie</a></li>
                        </ul>
                    </li>
                    <li class="divider"></li>
                    <li class="has-dropdown"><a>Genre</a>

                        <ul class="dropdown">
                            <li><a href="#">Annimation</a></li>
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Horreur</a></li>
                            <li><a href="#">Thriller</a></li>
                            <li><a href="#">Comédie</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="divider"></li>
            <li class="has-dropdown"><a><img width="30px" title="Série"
                                             src="<?= BASE_URL ?>images/serie.svg?color=rgba(240,240,240,1)"/></a>

                <ul class="dropdown">
                    <li class="has-dropdown"><a>Nouveauté</a>

                        <ul class="dropdown">
                            <li><a href="#">Nouveauté</a></li>
                            <li class="divider"></li>
                            <li><label>Genre</label></li>
                            <li><a href="#">Annimation</a></li>
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Horreur</a></li>
                            <li><a href="#">Thriller</a></li>
                            <li><a href="#">Comédie</a></li>
                        </ul>
                    </li>
                    <li class="divider"></li>
                    <li class="has-dropdown"><a>Genre</a>

                        <ul class="dropdown">
                            <li><a href="#">Annimation</a></li>
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Horreur</a></li>
                            <li><a href="#">Thriller</a></li>
                            <li><a href="#">Comédie</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="divider"></li>
            <li class="has-dropdown"><a><img width="30px" title="Musique"
                                             src="<?= BASE_URL ?>images/musique.svg?color=rgba(240,240,240,1)"/></a>

                <ul class="dropdown">
                    <li class="has-dropdown"><a>Nouveauté</a>

                        <ul class="dropdown">
                            <li><a href="#">Nouveauté</a></li>
                            <li class="divider"></li>
                            <li><label>Genre</label></li>
                            <li><a href="#">Dance</a></li>
                            <li><a href="#">Hip-hop</a></li>
                            <li><a href="#">Horreur</a></li>
                            <li><a href="#">Thriller</a></li>
                            <li><a href="#">Comédie</a></li>
                            <li class="divider"></li>
                            <li><label>Genre</label></li>
                            <li><a href="#">Dance</a></li>
                            <li><a href="#">Hip-hop</a></li>
                            <li><a href="#">Horreur</a></li>
                            <li><a href="#">Thriller</a></li>
                            <li><a href="#">Comédie</a></li>
                        </ul>
                    </li>
                    <li class="divider"></li>
                    <li class="has-dropdown"><a>Genre</a>

                        <ul class="dropdown">
                            <li><a href="#">Annimation</a></li>
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Horreur</a></li>
                            <li><a href="#">Thriller</a></li>
                            <li><a href="#">Comédie</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="divider"></li>


        </ul>

        <!-- Top Bar Right Nav Elements -->
        <ul class="right">
            <li class="divider hide-for-small"></li>
            <li class="has-form">
                <? echo isset($debug_performance_for_layout) ? $debug_performance_for_layout : ""; ?>
            </li>
            <li class="has-form">

                <? echo isset($debug_icon_for_layout) ? $debug_icon_for_layout : ""; ?>

            </li>
            <!-- Divider -->
            <li class="divider"></li>

            <!-- Dropdown -->
            <li class="has-form">
                <div class="row collapse">
                    <div class="large-8 small-9 columns">
                        <input id="recherche" placeholder="Recherche" type="text">
                    </div>
                    <div class="large-4 small-3 columns">
                        <a href="#" id="recherchesubmit" class="alert button expand"><img width="18px"
                                                                                          src="<?= BASE_URL ?>images/search.svg?color=rgba(240,240,240,1)"></a>
                    </div>
                </div>
            </li>
            <li><a href="<?= \core\Router::url("utilisateur/deconnexion") ?>"><img title="Déconnexion" width="30px"
                                                                                   src="<?= BASE_URL ?>images/logout.svg?color=white"></a>
            </li>
        </ul>
    </section>
</nav>