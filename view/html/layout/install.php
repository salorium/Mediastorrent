<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 26/04/14
 * Time: 13:51
 */
?>
<!DOCTYPE html>
<!--[if IE 8]> 				 <html class="no-js lt-ie9" lang="fr" > <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="fr" > <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Installation de MediasTorrent</title>


    <!--<link rel="stylesheet" href="<?php echo BASE_URL;?>stylesheets/app.css">
-->
    <link rel="stylesheet" href="<?php echo BASE_URL;?>stylesheets/mediastorrent1.css">
    <script src="<?php echo BASE_URL;?>bower_components/modernizr/modernizr.js"></script>
</head>
<body>
<script src="<?php echo BASE_URL;?>bower_components/jquery/dist/jquery.js"></script>
<!--
<script src="<?php echo BASE_URL;?>bower_components/foundation/js/foundation/foundation.js"></script>
<script src="<?php echo BASE_URL;?>bower_components/foundation/js/foundation/foundation.topbar.js"></script>-->
<script src="<?php echo BASE_URL;?>bower_components/foundation/js/foundation.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL;?>javascripts/noty/packaged/jquery.noty.packaged.js"></script>
<!--<script src="<?php echo BASE_URL;?>javascripts/mediastorrent/jquery.mousewheel.js"></script>
--><?= isset($loadjavascript_for_layout) ? $loadjavascript_for_layout:"";?>
<h1><?=\model\simple\String::styleString("Installation de MediasTorrent")?></h1>
<div class="container"><?php echo $content_for_layout; ?></div>

<center>


</center>
<div style="position			: fixed;
			bottom				: 0;
			left				: 0;
			right				: 0;
			text-align: center;"><span>~<?php
        $titre =preg_replace("#([A-Z]+)#",'<span class="secondary">$1</span>',\config\Conf::$nomdusite)." ".\config\Conf::$version."~ Powered by ".preg_replace("#([A-Z]+)#",'<span class="secondary">$1</span>',\config\Conf::$author)." © ".\config\Conf::$anneefondation.(date("Y") == \config\Conf::$anneefondation ? "":"-".date("Y"));

        echo $titre;
        ?></span></div>


<?= isset($debug_detail_for_layout) ?$debug_detail_for_layout:""; ?>
<script src="<?php echo BASE_URL;?>js/app.js"></script>
<script>
    <?= $initjavascript_for_layout; ?>
</script>
</body>

</html>