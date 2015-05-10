<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 09/05/15
 * Time: 16:28
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