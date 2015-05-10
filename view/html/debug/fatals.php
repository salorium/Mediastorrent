<?php
/**
 * Created by PhpStorm.
 * User: Salorium
 * Date: 06/12/13
 * Time: 08:18
 */
?>
<fieldset class="rouge">
    <legend class="debugger-deroule" data-id="d1">Erreur Fatal (<?= count($data) ?>)</legend>
    <div id="d1" class="debugger-auto">
        <table class="debugger">
            <thead>
            <tr>
                <th>Fichier</th>
                <th width="50">Ligne</th>
                <th>Message</th>
                <th width="50">Type</th>
            </tr>
            </thead>
            <tbody>


            <?
            foreach ($data as $v) {
                ?>
                <tr>
                    <td><?= $v["file"] ?></td>
                    <td><?= $v["line"] ?></td>
                    <td><?= nl2br($v["message"]) ?></td>
                    <td><?= $v["type"] ?></td>
                </tr>
            <?
            }
            ?>
            </tbody>
        </table>
    </div>
</fieldset>
