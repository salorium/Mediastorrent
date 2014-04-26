<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 26/04/14
 * Time: 13:49
 */
?>
<form id="root">

<table>
    <td>Password du root:</td><td><input type="password" name="password"></td>
</table>
</form>
<table>
    <tr>
        <td>Memcached</td>
        <td><?=($memcached ? \model\simple\String::styleSuccess("ok"):\model\simple\String::styleError("non ok"));?></td>
    </tr>
</table>