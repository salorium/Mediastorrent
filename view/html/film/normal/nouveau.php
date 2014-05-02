<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 22/03/14
 * Time: 03:36
 */
?>
<script src="<?= BASE_URL; ?>javascripts/mediastorrent/film.js"></script>
<script>
    // Execution de cette fonction lorsque le DOM sera entièrement chargé
    $(document).ready(function () {
        Film.init();
    });
</script>