<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 19/07/14
 * Time: 00:42
 */
//http://www.salorium.com/torrent/download/59F1437E049BA4A7C259012C474901EB495E48F2/2/salorium/b183021aa172e367cbe84cbdabace747e9dfdd65
$prio = ["Ne pas télécharger", "Normal", "Haute"];
if (!is_null($files)) {
    ?>
    <h3><?= $nom; ?></h3>
    <table style="margin: auto;">
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
                <td>
                    <a href="<?= \core\Router::url("torrent/download/" . $hashtorrent . "/" . $v[0] . "/" . \config\Conf::$user["user"]->keyconnexion); ?>"><?= basename($v[1]) ?></a>
                </td>
                <td>
                    <?= \model\simple\Converter::bytes($v[4], 2); ?>
                </td>
                <td>
                    <?= \model\simple\Converter::bytes(($v[3] != 0 ? $v[4] * $v[2] / $v[3] : 0), 2); ?>
                </td>
                <td>
                    <progress class="<?= ($v[2] == $v[3] ? "ul" : "dl"); ?> " value="<?= ($v[3] != 0 ? $v[2] : 1) ?>"
                              max="<?= $v[3] ?>" title="<?= ($v[3] != 0 ? $v[2] / $v[3] * 100 : 100); ?>%"></progress>
                </td>
                <td>
                    <?= $prio[$v[5]] ?>
                </td>
            </tr>
        <?
        }
        ?>

        </tbody>
    </table>
<?
} else {
    ?>
    <h3>Aucun fichier</h3>
<?
}
?>