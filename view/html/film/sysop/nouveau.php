<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 22/03/14
 * Time: 03:36
 */
\core\LoaderJavascript::add("base", "controller.setHost", array(substr($_SERVER["HTTP_HOST"] . dirname(dirname($_SERVER["SCRIPT_NAME"])) . ($_SERVER["SCRIPT_NAME"] !== "/index.php" ? "/" : ""), 0, -1), $_SERVER["SERVER_PORT"] == 443))
?>
<span id="changebackdrop" onclick="Film.changeBackdrop();" mediastorrent-id=""
      style="position: absolute; top: 0;left: 0;background-color: rgba(51,51,51,1);color: #ffffff; padding: 2px;"><img
        width="30px"
        title="Changer le backdrop" src="<?= BASE_URL ?>images/paint.svg?color=white"></span>
<div class="container">
    <script src="<?= BASE_URL; ?>javascripts/mediastorrent/film.js"></script>
    <script>
        // Execution de cette fonction lorsque le DOM sera entièrement chargé
        $(document).ready(function () {
            Film.init(<?=json_encode($film)?>);
        });
    </script>
</div>