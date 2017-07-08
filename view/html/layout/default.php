<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 25/10/13
 * Time: 22:20
 * To change this template use File | Settings | File Templates.
 */
?>
<!DOCTYPE html>
<!--[if IE 8]>
<html class="no-js lt-ie9" lang="fr"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js" lang="fr"> <!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>MediasTorrent</title>


    <!-- <link rel="stylesheet" href="<?php echo BASE_URL; ?>stylesheets/app.css">
-->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>stylesheets/mediastorrent1.css">
    <link rel="icon" type="image/png" href="<?php echo BASE_URL; ?>images/favicon.png"/>
    <script src="<?php echo BASE_URL; ?>bower_components/modernizr/modernizr.js"></script>
</head>
<body>
<script src="<?php echo BASE_URL; ?>bower_components/jquery/dist/jquery.min.js"></script>
<script src="<?php echo BASE_URL; ?>bower_components/foundation/js/foundation.min.js"></script>

<?= isset($loadjavascript_for_layout) ? $loadjavascript_for_layout : ""; ?>
<h1><?php
    $titre = \config\Conf::$nomdusite;
    $titre = preg_replace("#([A-Z]+)#", '<span class="secondary">$1</span>', $titre);
    echo $titre;
    ?></h1>
<?= isset($debug_icon_for_layout) ? $debug_icon_for_layout : ""; ?>
<div class="container"><?php echo $content_for_layout; ?></div>

<center>
    <?= isset($debug_performance_for_layout) ? $debug_performance_for_layout : ""; ?>

</center>
<div class="show-for-medium-up" style="position			: fixed;
			bottom				: 0;
			left				: 0;
			right				: 0;
			text-align: center;"><span>~<?php
        $titre = preg_replace("#([A-Z]+)#", '<span class="secondary">$1</span>', \config\Conf::$nomdusite) . " " . \config\Conf::$version . "~ Powered by " . preg_replace("#([A-Z]+)#", '<span class="secondary">$1</span>', \config\Conf::$author) . " © " . \config\Conf::$anneefondation . (date("Y") == \config\Conf::$anneefondation ? "" : "-" . date("Y"));

        echo $titre;
        ?></span></div>


<?= isset($debug_detail_for_layout) ? $debug_detail_for_layout : ""; ?>
<script src="<?php echo BASE_URL; ?>js/app.js"></script>
<script>
    <?= $initjavascript_for_layout; ?>
</script>
</body>

</html>