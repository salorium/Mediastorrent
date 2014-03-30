<?php
/**
 * Created by PhpStorm.
 * User: Salorium
 * Date: 06/12/13
 * Time: 08:18
 */
?>
<fieldset class="orangee"><legend class="debugger-deroule" data-id="d2">Warning (<?=count($data)?>)</legend>
    <div id="d2" class="debugger-auto">
        <table class="debugger">
            <thead>
            <tr>
                <th>Fichier</th>
                <th width="50">Ligne</th>
                <th>Message</th>
                <th>Fonction</th>
                <th>Args</th>
            </tr>
            </thead>
            <tbody>


            <?
            foreach($data as $v){
            ?>
                <tr>
                    <td><?= $v[1]?></td>
                    <td><?= $v[2]?></td>
                    <td><?= nl2br( $v[0])?></td>
                    <td><?= $v[3]?></td>
                    <td><?= $v[4]?></td>
                </tr>
            <?
            }
            ?>
            </tbody>
        </table>
    </div>
</fieldset>
<!--
<fieldset class="rouge"><legend class="debugger-deroule" data-id="d3">Warning (<?=count($data)?>)</legend>
    <div id="d3" class="debugger-auto">
        <table class="debugger">
            <thead>
            <tr>
                <th>Fichier</th>
                <th width="50">Ligne</th>
                <th>Message</th>
                <th>Fonction</th>
                <th>Args</th>
            </tr>
            </thead>
            <tbody>


            <?
            foreach($data as $v){
                ?>
                <tr>
                    <td><?= $v[1]?></td>
                    <td><?= $v[2]?></td>
                    <td><?= nl2br( $v[0])?></td>
                    <td><?= $v[3]?></td>
                    <td><?= $v[4]?></td>
                </tr>
            <?
            }
            ?>
            </tbody>
        </table>
    </div>
</fieldset>
<fieldset class="violet"><legend class="debugger-deroule" data-id="d4">Warning (<?=count($data)?>)</legend>
    <div id="d4" class="debugger-auto">
        <table class="debugger">
            <thead>
            <tr>
                <th>Fichier</th>
                <th width="50">Ligne</th>
                <th>Message</th>
                <th>Fonction</th>
                <th>Args</th>
            </tr>
            </thead>
            <tbody>


            <?
            foreach($data as $v){
                ?>
                <tr>
                    <td><?= $v[1]?></td>
                    <td><?= $v[2]?></td>
                    <td><?= nl2br( $v[0])?></td>
                    <td><?= $v[3]?></td>
                    <td><?= $v[4]?></td>
                </tr>
            <?
            }
            ?>
            </tbody>
        </table>
    </div>
</fieldset>
<fieldset class="vert"><legend class="debugger-deroule" data-id="d5">Warning (<?=count($data)?>)</legend>
    <div id="d5" class="debugger-auto">
        <table class="debugger">
            <thead>
            <tr>
                <th>Fichier</th>
                <th width="50">Ligne</th>
                <th>Message</th>
                <th>Fonction</th>
                <th>Args</th>
            </tr>
            </thead>
            <tbody>


            <?
            foreach($data as $v){
                ?>
                <tr>
                    <td><?= $v[1]?></td>
                    <td><?= $v[2]?></td>
                    <td><?= nl2br( $v[0])?></td>
                    <td><?= $v[3]?></td>
                    <td><?= $v[4]?></td>
                </tr>
            <?
            }
            ?>
            </tbody>
        </table>
    </div>
</fieldset>-->