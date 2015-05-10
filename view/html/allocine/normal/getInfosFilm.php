<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 13/04/14
 * Time: 16:41
 */

?>
    <h2><?= $film["Titre"] ?></h2>
<?
foreach ($film as $k => $v) {
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
<? if (isset($film["ba"])) { ?>
    <object width="640px" height="390px" type="application/x-shockwave-flash" id="V6_player"
            style="visibility: visible;" data="http://images.allocine.fr/commons/player/AcV5/AcPlayer_v5.2.swf">
        <param name="menu" value="false">
        <param name="wmode" value="window">
        <param name="scale" value="noScale">
        <param name="allowFullscreen" value="true">
        <param name="allowScriptAccess" value="always">
        <param name="bgcolor" value="#000000">
        <param name="flashvars"
               value="autoPlay=false&amp;adVast=true&amp;blog=false&amp;canHideNav=true&amp;cmedia=<?= $film["ba"] ?>&amp;endScreen=true&amp;expandable=true&amp;host=http://www.allocine.fr&amp;isACLogoDisplay=false&amp;lg=FR&amp;modeOver=false&amp;postRoll=true&amp;partner=&amp;ref=<?= $film["code"] ?>&amp;refererUrl=http://www.allocine.fr&amp;smartIdPrerollSet=171792&amp;subContext=&amp;timeToShowAdPanel=15&amp;typeRef=Movie&amp;urlDirectVast=&amp;urlDirectVastPr=&amp;prtSystem=wads&amp;noSkipAdIds=&amp;urlDirectVastDfp=http%3A%2F%2Fpubads.g.doubleclick.net%2Fgampad%2Fads%3Fcmsid%3D2072%26correlator%3D1798639350%26cust_params%3Dgenre%253D13026%2526genres%253D13026%25252C13001%2526kids%253D1%2526movie%253D203691%2526video%253D<?= $film["ba"] ?>%26env%3Dvp%26gdfp_req%3D1%26impl%3Ds%26iu%3D%252F120157152%252Ffr-classic%252Fmovie%252Fanimation-13026%26output%3Dxml_vast2%26sz%3D640x390%26unviewed_position_start%3D1%26url%3Dhttp%253A%252F%252Fwww.allocine.fr%252Fvideo%252Fplayer_gen_cmedia%253D<?= $film["ba"] ?>%2526cfilm%253D203691.html%26vid%3D<?= $film["ba"] ?>%26vpos%3Dpreroll&amp;vastUrlPostRoll1=http%3A%2F%2Fwww.allocine.fr%2F_prt%2F8758166075%2Fgenre%3D13026%257C13001%26kids%3D1%26movie%3D203691%26video%3D<?= $film["ba"] ?>&amp;vastUrlPostRoll2=http%3A%2F%2Fwww.allocine.fr%2F_prt%2F8758166075%2Fgenre%3D13026%257C13001%26kids%3D1%26movie%3D203691%26video%3D<?= $film["ba"] ?>">
    </object>
<? } ?>