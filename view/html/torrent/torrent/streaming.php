<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 03/04/14
 * Time: 14:28
 */
$b = \get_browser(null, true);
if ($b["platform"] == "Linux") {
    ?>
    <embed type="application/x-vlc-plugin" pluginspage="http://www.videolan.org" version="VideoLAN.VLCPlugin.2"
           width="640px" height="480px" id="vlc" loop="yes" autoplay="yes" target="<?= $src ?>"></embed><!----->

<?
} else {
    ?>
    <object id="ie_plugin" classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="640" height="480"
            codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">

        <param name="custommode" value="none"/>

        <param name="autoPlay" value="true"/>
        <param name="movieTitle" value="Test.avi">
        <embed id="np_plugin" type="video/divx" custommode="none" width="640" height="480" autoPlay="true"
               pluginspage="http://go.divx.com/plugin/download/">
        </embed>
    </object>
    <script>
        var plugin;

        if (window.addEventListener) {
            window.addEventListener('load', onload, false);
        } else {
            window.attachEvent('onload', onload);
        }
        function onload() {
            setTimeout(function () {
                if (navigator.userAgent.indexOf('MSIE') != -1) {
                    plugin = document.getElementById('ie_plugin');
                }
                else {
                    plugin = document.getElementById('np_plugin');
                }
                plugin.Open('<?=($src);?>');
            }, 1000);

        }


    </script>
<?
}
?>