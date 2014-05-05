<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 26/04/14
 * Time: 13:49
 */
\core\LoaderJavascript::add("base", "controller.setHost", array($_SERVER["HTTP_HOST"] . dirname(dirname($_SERVER["SCRIPT_NAME"])) . ($_SERVER["SCRIPT_NAME"] !== "/index.php" ? "/" : ""), false));
\core\LoaderJavascript::add("install");
?>
    <!--
<form id="root">

    <table>
        <td>Password du root:</td>
        <td><input type="password" name="password"></td>
    </table>
</form>
<table>
    <tr>
        <td>Memcached</td>
        <td><a <?= (!$memcached ? 'onclick="Install.controller.enableModule(this);"' : '') ?>
                data-module="memcached"><?= ($memcached ? \model\simple\String::styleSuccess("Ok") : \model\simple\String::styleError("Non ok")); ?></a>
        </td>
    </tr>
    <tr>
        <td>Mysqli</td>
        <td><a <?= (!$mysqli ? 'onclick="Install.controller.enableModule(this);"' : '') ?>
                data-module="mysqli"><?= ($mysqli ? \model\simple\String::styleSuccess("Ok") : \model\simple\String::styleError("Non ok")); ?></a>
        </td>
    </tr>
    <tr>
        <td>Imagick</td>
        <td><a <?= (!$imagick ? 'onclick="Install.controller.enableModule(this);"' : '') ?>
                data-module="imagick"><?= ($imagick ? \model\simple\String::styleSuccess("Ok") : \model\simple\String::styleError("Non ok")); ?></a>
        </td>
    </tr>
</table>

<table>
    <tr>
        <td>Ecriture dans le cache</td>
        <td><a <?= (!$ecrituredossiercache ? 'onclick="Install.controller.enableWriteFile(this);"' : '') ?>
                data-filewrite="cache"><?= ($ecrituredossiercache ? \model\simple\String::styleSuccess("Ok") : \model\simple\String::styleError("Non ok")); ?></a>
        </td>
    </tr>

</table>
-->
    <table>
        <tr>
            <td>Memcached</td>
            <td><?= ($memcached ? \model\simple\String::styleSuccess("Ok") : \model\simple\String::styleError("Non ok : pour l'installer veuillez faire : sudo apt-get install php5-memcached")); ?>
            </td>
        </tr>
        <tr>
            <td>Mysqli</td>
            <td><?= ($mysqli ? \model\simple\String::styleSuccess("Ok") : \model\simple\String::styleError("Non ok : pour l'installer veuillez faire : sudo apt-get install php5-mysqlnd")); ?>
            </td>
        </tr>
        <tr>
            <td>Imagick</td>
            <td><?= ($imagick ? \model\simple\String::styleSuccess("Ok") : \model\simple\String::styleError("Non ok : pour l'installer veuillez faire : sudo apt-get install php5-imagick")); ?>
            </td>
        </tr>
        <tr>
            <td>Curl</td>
            <td><?= ($curl ? \model\simple\String::styleSuccess("Ok") : \model\simple\String::styleError("Non ok : pour l'installer veuillez faire : sudo apt-get install php5-imagick")); ?>
            </td>
        </tr>
        <tr>
            <td>Json</td>
            <td><?= ($json ? \model\simple\String::styleSuccess("Ok") : \model\simple\String::styleError("Non ok : pour l'installer veuillez faire : sudo apt-get install php5-imagick")); ?>
            </td>
        </tr>
    </table>

    <table>
        <tr>
            <td>Ecriture dans le cache</td>
            <td><?= ($ecrituredossiercache ? \model\simple\String::styleSuccess("Ok") : \model\simple\String::styleError("Non ok : pour rendre l'écriture possible veuillez faire : sudo chmod -R a+w " . ROOT . DS . "cache")); ?>
            </td>
        </tr>
        <tr>
            <td>Ecriture dans le fichier Conf</td>
            <td><?= ($ecriturefileconfig ? \model\simple\String::styleSuccess("Ok") : \model\simple\String::styleError("Non ok : pour rendre l'écriture possible veuillez faire : sudo chmod -R a+w " . ROOT . DS . "config" . DS . "Conf.php")); ?>
            </td>
        </tr>
    </table>
<?php
if ($ecriturefileconfig && $ecriturefileconfig && $json && $curl && $imagick && $mysqli && $memcached) {
    ?>
    <a class="button secondary" href="<?= \core\Router::url("install/mysqlinit") ?>">Suite</a>
<?
} else {
    ?>
    <a class="button">Recharger la page</a>

<?
}
?>