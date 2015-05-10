<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 12/12/2014
 * Time: 17:04
 */
?>
<div class="row">

    <fieldset>
        <legend>Upload</legend>
        <div class="row">
            <div class="columns">
                <label>Announce
                    <input onclick="this.select()" type="text" value="<?= \model\simple\Torrent::getAnnounceUser() ?>"/>
                </label>

            </div>
        </div>

        <form data-abide class="custom" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="columns">
                    <label>Torrent
                        <small>obligatoire</small>
                        <input name="torrent" type="file" required/>
                    </label>
                    <small class="error">Le fichier torrent est obligatoire !!</small>
                </div>
            </div>

            <div class="row">
                <div class="columns">
                    <ul class="button-group">
                        <li>
                            <button class="button small" value="Upload" type="submit">Upload</button>
                        </li>


                    </ul>
                </div>
            </div>
        </form>
    </fieldset>

</div>