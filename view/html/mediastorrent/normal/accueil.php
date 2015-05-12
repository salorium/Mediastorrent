<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 19/03/14
 * Time: 20:25
 */
\core\LoaderJavascript::add("accueil", "controller.init");
?>
<ul class="carrousel show-for-medium-up">

    <li><a href="<?= \core\Router::url("film/nouveau") ?>"> <img title="Film" src="images/film.svg"/>
        </a></li>
    <li><a href="<?= \core\Router::url("serie/nouveau") ?>"> <img title="Série" src="images/serie.svg"/>
        </a></li>
    <li><a href="#DDD"> <img title="Musique" src="images/musique.svg"/>
        </a></li>
</ul>
