<?php
/**
 * Created by PhpStorm.
 * User: Salorium
 * Date: 06/12/13
 * Time: 08:31
 */
?>
<div id="debugger" class="debugger">
    <div class="debugger-title"><a
            class="titre"><?= preg_replace("#([A-Z]+)#", '<span class="secondary">$1</span>', "Debugger"); ?></a><a
            onclick="Debug.view.close();">
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 style="display: block;float: right;top:0px" x="0px" y="0px"

                 width="50px" height="50px" viewBox="0 0 512 512" enable-background="new 0 0 512 512"
                 xml:space="preserve">

<path style="fill: red" id="x-mark-4-icon" d="M462,256c0,113.771-92.229,206-206,206S50,369.771,50,256S142.229,50,256,50S462,142.229,462,256z

	 M422,256c0-91.755-74.258-166-166-166c-91.755,0-166,74.259-166,166c0,91.755,74.258,166,166,166C347.755,422,422,347.741,422,256z

	 M325.329,362.49l-67.327-67.324l-67.329,67.332l-36.164-36.186l67.314-67.322l-67.321-67.317l36.185-36.164l67.31,67.301

	l67.3-67.309l36.193,36.17l-67.312,67.315l67.32,67.31L325.329,362.49z"/>

</svg>

        </a></div>
    <div id="debugger-data" class="debugger-data">
        <div id="debugger-php">
            <?= $debug_contenu_for_layout; ?>
        </div>
        <div id="debugger-js"></div>
    </div>
</div>