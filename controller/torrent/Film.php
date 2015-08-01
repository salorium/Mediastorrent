<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 01/08/15
 * Time: 14:29
 */

namespace controller\torrent;


class Film extends \core\Controller
{
    function share($idTorrentFilm, $share)
    {
        $share = filter_var($share, FILTER_VALIDATE_BOOLEAN);
        $this->set("res", (\model\mysql\Torrentfilm::share($idTorrentFilm, $share) ? $share : null));
    }
}