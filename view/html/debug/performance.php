<?php
/**
 * Created by PhpStorm.
 * User: Salorium
 * Date: 06/12/13
 * Time: 04:45
 */


?>


<table id="infoserver" class="infoserver">
    <tr>

        <?
        if (isset($TimeCPU)) {
            ?>
            <td><img title="Temps cpu" src="<?= BASE_URL; ?>images/infoscputime.svg"></td>
            <td><?= $TimeCPU ?></td>
        <? } ?>
        <td><img title="Rame" src="<?= BASE_URL; ?>images/infosrame.svg"></td>
        <td><?= $RameUsage ?></td>

        <td><img title="Temps génération page" src="<?= BASE_URL; ?>images/infospagetime.svg"></td>
        <td><?= $TimePage ?></td>
</table>