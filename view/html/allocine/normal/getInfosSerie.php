<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 13/04/14
 * Time: 16:41
 */

?>
<h2><?= $serie["Titre"] ?></h2>
<?
foreach ($serie as $k => $v) {
    switch ($k) {
        case "Titre":
        case "code":
        case "type":
            break;
        case "imageposter":
        case "imagebackdrop":
            echo "<fieldset><legend>Image</legend>";
            foreach ($v as $kk => $vv) {
                echo "<img width='150' src='" . $vv[0] . "'>";
            }
            echo "</fieldset>";
            break;
        default:
            echo $k . " " . $v . "<br>";
            break;
    }
}
?>
