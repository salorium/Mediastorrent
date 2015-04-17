<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 01/12/2014
 * Time: 00:19
 */
?>
    <h1>Les 4 dernières vidéos d'Iti :)</h1>
    <object id="player" style="margin: auto; width: 940px; display: block;" width="940" height="532"
            data="https://www.dailymotion.com/swf/<?= $data->list[0]->id ?>&amp;enableApi=1&amp;playerapiid=player&amp;expendVideo=1&amp;autoPlay=1&amp;automute=0&amp;forcedQuality=hd"
            type="application/x-shockwave-flash">
        <param value="always" name="allowScriptAccess">
        <param value="true" name="allowfullscreen">
    </object>
    <script>
        function change(id) {
            //  $("#player").attr("data","https://www.dailymotion.com/swf/"+id+"&amp;enableApi=1&amp;playerapiid=dmplayer&amp;expendVideo=1&amp;autoPlay=1&amp;automute=0&amp;forcedQuality=hd");
            document.getElementById("player").loadVideoById(id);
        }
    </script>
<?
$bol = true;
for ($i = 0; $i < 4; $i++) {
    // var_dump($v);
    $v = $data->list[$i];
    ?>

    <img onclick="change('<?= $v->id ?>');" width="200" src="<?= $v->thumbnail_480_url ?>">

<?
}
?>
    <h1>Les videos de tes amis :)</h1>
<?
$bol = true;
for ($i = 0; $i < 4; $i++) {
    // var_dump($v);
    $v = $dataamis->list[$i];
    ?>

    <img onclick="change('<?= $v->id ?>');" width="100" src="<?= $v->thumbnail_480_url ?>">

<?
}
?>