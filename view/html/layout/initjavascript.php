<?php
/**
 * Created by PhpStorm.
 * User: Salorium
 * Date: 06/12/13
 * Time: 17:47
 */
?>
<? if (isset($js["fonction"])) {
    ?>
    <?= ucfirst($js["name"]); ?>.<?= $js["fonction"] ?>(<?= isset($js["args"]) ? json_encode($js["args"]) : "" ?>);
<?
}
?>