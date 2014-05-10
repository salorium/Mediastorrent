<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 22/03/14
 * Time: 03:36
 */
\core\LoaderJavascript::add("base", "controller.setHost", array($_SERVER["HTTP_HOST"] . dirname(dirname($_SERVER["SCRIPT_NAME"])) . ($_SERVER["SCRIPT_NAME"] !== "/index.php" ? "/" : ""), false))
?>
<div class="container">
    <script src="<?= BASE_URL; ?>javascripts/mediastorrent/film.js"></script>
<script>
    // Execution de cette fonction lorsque le DOM sera entièrement chargé
    $(document).ready(function () {
        Film.init(<?=json_encode($film)?>);
    });
</script>
</div>