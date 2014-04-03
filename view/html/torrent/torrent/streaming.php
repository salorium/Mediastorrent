<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 03/04/14
 * Time: 14:28
 */
?>
<object classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616" width="640" height="480" codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab">

    <param name="custommode" value="none" />

    <param name="autoPlay" value="true" />
    <param name="src" value="<?=$src;?>" />
    <param name="movieTitle" value="Test.avi">
    <embed type="video/divx" src="<?=$src;?>"   custommode="none" width="640" height="480" autoPlay="true"  pluginspage="http://go.divx.com/plugin/download/">
        </embed>
    </object>