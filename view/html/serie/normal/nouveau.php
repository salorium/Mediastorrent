<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 27/04/15
 * Time: 16:39
 */

\core\LoaderJavascript::add("base", "controller.setHost", array($_SERVER["HTTP_HOST"] . dirname(dirname($_SERVER["SCRIPT_NAME"])) . ($_SERVER["SCRIPT_NAME"] !== "/index.php" ? "/" : ""), $_SERVER["SERVER_PORT"] == 443))
?>
<div class="container">
    <script src="<?= BASE_URL; ?>javascripts/mediastorrent/serie.js"></script>
    <script>
        // Execution de cette fonction lorsque le DOM sera entièrement chargé
        $(document).ready(function () {
            Film.init(<?=json_encode($film)?>);
        });
    </script>
</div>