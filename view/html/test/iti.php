<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 01/12/2014
 * Time: 00:19
 */
?>
    <h1>Les 4 dernières vidéos d'Iti :)</h1>
    <div id="player"></div>
    <script src="https://api.dmcdn.net/all.js"></script>
    <script>

        // Append a div in the DOM, you may use a real <div> tag


        var player = DM.player("player", {video: "x2yo269", width: "728", height: "410", params: {}});

    </script>
    <script>
        function change(id) {
            //  $("#player").attr("data","https://www.dailymotion.com/swf/"+id+"&amp;enableApi=1&amp;playerapiid=dmplayer&amp;expendVideo=1&amp;autoPlay=1&amp;automute=0&amp;forcedQuality=hd");
            player.load(id);
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
    <h1>7dtd s1</h1>
<?
$bol = true;
foreach ($dtd1 as $k => $v) {
    // var_dump($v);

    ?>

    <img onclick="change('<?= $v->id ?>');" width="100" src="<?= $v->thumbnail_480_url ?>">

<?
}
?>
    <h1>7dtd s2</h1>
<?
$bol = true;
foreach ($dtd2 as $k => $v) {
    // var_dump($v);

    ?>

    <img onclick="change('<?= $v->id ?>');" width="100" src="<?= $v->thumbnail_480_url ?>">

<?
}
?>
    <h1>7dtd s3</h1>
<?
$bol = true;
foreach ($dtd3 as $k => $v) {
    // var_dump($v);

    ?>

    <img onclick="change('<?= $v->id ?>');" width="100" src="<?= $v->thumbnail_480_url ?>">

<?
}
?>
    <h1>The Witcher 3 Wild Hunt</h1>
<?
$bol = true;
foreach ($t1 as $k => $v) {
    // var_dump($v);

    ?>

    <img onclick="change('<?= $v->id ?>');" width="100" src="<?= $v->thumbnail_480_url ?>">

<?
}
?>
    <h1>Life is strange</h1>
<?
$bol = true;
foreach ($lis as $k => $v) {
    // var_dump($v);

    ?>

    <img onclick="change('<?= $v->id ?>');" width="100" src="<?= $v->thumbnail_480_url ?>">

<?
}
?>