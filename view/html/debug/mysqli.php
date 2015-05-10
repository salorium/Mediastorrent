<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 12/03/14
 * Time: 16:32
 */
?>
<fieldset class="violet">
    <legend class="debugger-deroule" data-id="d3">Requête(s) Mysql(<?= count($data) ?>) <?= \number_format($time, 3) ?>
        ms
    </legend>
    <div id="d3" class="debugger-auto">
        <table class="debugger">
            <thead>
            <tr>
                <th width="90">Temps</th>
                <th>Requête</th>
                <th>Résultat</th>
                <th>N°erreur</th>
                <th>Erreur</th>
            </tr>
            </thead>
            <tbody>


            <?
            foreach ($data as $v) {
                ?>
                <tr>
                    <td><?= \number_format($v[1], 3) ?> ms</td>
                    <td><?= $v[0] ?></td>
                    <td><?php
                        if (is_array($v[2])) {
                            foreach ($v[2] as $k => $vv) {
                                echo nl2br($vv);
                            }
                        } else {
                            echo nl2br($v[2]);
                        }
                        ?></td>
                    <td><?= ($v[3]); ?></td>
                    <td><?= ($v[4]); ?></td>
                </tr>
            <?
            }
            ?>
            </tbody>
        </table>
    </div>
</fieldset>