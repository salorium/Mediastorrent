<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 22/03/14
 * Time: 20:03
 */
?>
<fieldset class="vert">
    <legend class="debugger-deroule" data-id="d4">Requête(s) Memcached(<?= count($data) ?>
        ) <?= \number_format($time, 3) ?> ms
    </legend>
    <div id="d4" class="debugger-auto">
        <table class="debugger">
            <thead>
            <tr>
                <th width="100">Temps</th>
                <th>Type</th>
                <th>Database</th>
                <th>Clef</th>
                <th>Valeur</th>
                <th>Code résultat</th>
                <th>Résultat</th>
            </tr>
            </thead>
            <tbody>


            <?
            foreach ($data as $v) {
                ?>
                <tr>
                    <td><?= \number_format($v[1], 3) ?> ms</td>
                    <td><?= $v[0] ?></td>
                    <td><?= ($v[2]); ?></td>
                    <td><?= ($v[3]); ?></td>
                    <td><?php
                        if (is_array($v[4])) {
                            foreach ($v[4] as $k => $vv) {
                                echo $k . " => " . nl2br($vv) . "<br>";
                            }
                        } else {
                            if (is_null($v[4])) {
                                echo '<span class="secondary">NULL</span>';
                            } else {
                                echo nl2br($v[4]);
                            }

                        }
                        ?></td>
                    <td><?= ($v[5]); ?></td>
                    <td><?= ($v[6]); ?></td>
                </tr>
            <?
            }
            ?>
            </tbody>
        </table>
    </div>
</fieldset>