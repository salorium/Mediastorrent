<?php
/**
 * Created by PhpStorm.
 * User: Salorium
 * Date: 06/12/13
 * Time: 04:07
 */
if (!is_null($debugicon)){?>

<span  class="debuggericon">
<img id="debuggericon" title="Debugger" onclick="Debug.view.show();" src="<?=BASE_URL;?>images/debugger<?=$debugicon ?>.svg">
</span>
<?} ?>