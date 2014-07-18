<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 19/07/14
 * Time: 00:42
 */
if (!is_null($files)) {
    ?>

    <table>
        <thead>
        <tr>
            <td>Nom</td>
            <td>Taille</td>
            <td>Reçus</td>
            <td>%</td>
            <td>Priorité</td>
        </tr>
        </thead>
        <tbody>
        <?
        foreach ($files as $k => $v) {
            ?>
            <tr>
                <td><?= basename($v[1]) ?></td>
            </tr>
        <?
        }
        ?>

        </tbody>
    </table>
<?
} else {
    ?>
    <h1>Aucun file</h1>
<?
}
?>