<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 26/04/14
 * Time: 13:49
 */
\core\LoaderJavascript::add("base","controller.setHost",array($_SERVER["HTTP_HOST"].dirname(dirname($_SERVER["SCRIPT_NAME"])).($_SERVER["SCRIPT_NAME"] !== "/index.php" ? "/":""),false));
\core\LoaderJavascript::add("install");
?>
<form id="root">

<table>
    <td>Password du root:</td><td><input type="password" name="password"></td>
</table>
</form>
<table>
    <tr>
        <td>Memcached</td>
        <td><a onclick="Install.controller.enableModule(this);" data-module="memcached"><?=($memcached ? \model\simple\String::styleSuccess("Ok"):\model\simple\String::styleError("Non ok"));?></a></td>
    </tr>
    <tr>
        <td>Mysqli</td>
        <td><a onclick="Install.controller.enableModule(this);" data-module="mysqli"><?=($mysqli ? \model\simple\String::styleSuccess("Ok"):\model\simple\String::styleError("Non ok"));?></a></td>
    </tr>
    <tr>
        <td>Imagick</td>
        <td><a onclick="Install.controller.enableModule(this);" data-module="imagick"><?=($imagick ? \model\simple\String::styleSuccess("Ok"):\model\simple\String::styleError("Non ok"));?></a></td>
    </tr>
</table>

<table>
    <tr>
        <td>Ecriture dans le cache</td>
        <td><a onclick="Install.controller.enableWriteFile(this);" data-filewrite="cache"><?=($ecrituredossiercache ? \model\simple\String::styleSuccess("Ok"):\model\simple\String::styleError("Non ok"));?></a></td>
    </tr>
    <tr>
        <td>Mysqli</td>
        <td><a onclick="Install.controller.enableModule(this);" data-module="mysqli"><?=($mysqli ? \model\simple\String::styleSuccess("Ok"):\model\simple\String::styleError("Non ok"));?></a></td>
    </tr>
    <tr>
        <td>Imagick</td>
        <td><a onclick="Install.controller.enableModule(this);" data-module="imagick"><?=($imagick ? \model\simple\String::styleSuccess("Ok"):\model\simple\String::styleError("Non ok"));?></a></td>
    </tr>
</table>