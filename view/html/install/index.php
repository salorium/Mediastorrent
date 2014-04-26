<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 26/04/14
 * Time: 13:49
 */
?>
<table>
    <tr>
        <td>Memcached</td>
        <td><?=($memcached ? \model\simple\String::styleSuccess("ok"):model\simple\String::styleError("non ok"));?></td>
    </tr>
</table>