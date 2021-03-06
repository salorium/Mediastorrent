<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 30/04/14
 * Time: 00:37
 */
define('WEBROOT', __DIR__);
define('ROOT', dirname(WEBROOT));
define('DS', DIRECTORY_SEPARATOR);

function __autoload($class_name)
{
    $filename = ROOT . DS . str_replace("\\", DS, $class_name) . ".php";
    if (file_exists($filename)) {
        require_once $filename;
    } else {

    }

}

$userscgi = $argv[1];
$hash = $argv[2];
$base_path = $argv[3];
$base_name = $argv[4];
$is_multi = $argv[5];
$clefunique = $argv[6];
$typemedias = $argv[7];

define('LOG', ROOT . DS . "log" . DS . $userscgi . "_addbibli.log");
\model\simple\Console::println("Début");
\model\simple\Console::println($hash);
\model\simple\Console::println($typemedias);
file_put_contents(ROOT . DS . "log" . DS . $userscgi . "start_addblibli.log", $userscgi . " " . $hash . ' "' . $base_path . '" "' . $base_name . '" ' . $is_multi . " " . $clefunique . " " . $typemedias . "\n", FILE_APPEND);
$filetorrent = \model\xmlrpc\rTorrentSettings::get($userscgi)->session . DS . $hash . ".torrent";
if (file_exists($filetorrent)) {
    $torrent = new \model\simple\Torrent($filetorrent);
    if (!$torrent->errors()) {
        $info = $torrent->info;
        $numfile = 0;
        if (isset($info['files'])) {
            foreach ($info['files'] as $key => $file) {
                $file = $base_path . DS . implode('/', $file['path']);
                \model\simple\Console::println($file);
                switch ($typemedias) {
                    case "film":
                        $torrentf = \model\mysql\Torrentfilm::rechercheParNumFileHashClefunique($numfile, $hash, $clefunique);
                        \model\simple\Console::println((is_bool($torrentf) ? "Non Présent" : "Présent"));
                        if (!is_bool($torrentf)) {
                            $mediainfo = new \model\simple\Mediainfo($file);
                            $mediainfo = $mediainfo->getFormatVideo();
                            //$torrentf->mediainfo = json_encode($mediainfo);

                            \model\simple\Console::println($torrentf->fini($mediainfo) ? "Sav ok" : "Sav Non ok");
                        }
                        break;
                    case "serie":
                        $torrents = \model\mysql\Torrentserie::rechercheParNumFileHashClefunique($numfile, $hash, $clefunique);
                        \model\simple\Console::println((is_bool($torrents) ? "Non Présent" : "Présent"));
                        if (!is_bool($torrents)) {
                            $mediainfo = new \model\simple\Mediainfo($file);
                            $mediainfo = $mediainfo->getFormatVideo();
                            //$torrentf->mediainfo = json_encode($mediainfo);

                            \model\simple\Console::println($torrents->fini($mediainfo) ? "Sav ok" : "Sav Non ok");
                        }
                        break;
                }
                $numfile++;
            }
        } else {
            $file = dirname($base_path) . DS . $info['name'];
            \model\simple\Console::println($file);
            switch ($typemedias) {
                case "film":
                    $torrentf = \model\mysql\Torrentfilm::rechercheParNumFileHashClefunique($numfile, $hash, $clefunique);
                    \model\simple\Console::println((is_bool($torrentf) ? "Non Présent" : "Présent"));
                    if (!is_bool($torrentf)) {
                        $mediainfo = new \model\simple\Mediainfo($file);
                        $mediainfo = $mediainfo->getFormatVideo();
                        \model\simple\Console::println($torrentf->fini($mediainfo) ? "Sav ok" : "Sav Non ok");
                    }
                    break;
                case "serie":
                    $torrents = \model\mysql\Torrentserie::rechercheParNumFileHashClefunique($numfile, $hash, $clefunique);
                    \model\simple\Console::println((is_bool($torrents) ? "Non Présent" : "Présent"));
                    if (!is_bool($torrents)) {
                        $mediainfo = new \model\simple\Mediainfo($file);
                        $mediainfo = $mediainfo->getFormatVideo();
                        //$torrentf->mediainfo = json_encode($mediainfo);

                        \model\simple\Console::println($torrents->fini($mediainfo) ? "Sav ok" : "Sav Non ok");
                    }
                    break;
            }
        }

    } else {
        \model\simple\Console::println("Erreur fichier torrent");
    }
} else {
    \model\simple\Console::println("Impossible de récupérer le torrent");
}
/*}else{
    $cmd = exec('mediainfo -f --Output=XML "'.$base_path.'"',$output,$error);
    $json = json_decode(json_encode( simplexml_load_string(implode("",$output))),true);

    if ( $error == 0){
        foreach ($json["File"]["track"] as $v){
            if ( $v["@attributes"]["type"] == "Video"){

            }
            \model\simple\Console::println($v["@attributes"]["type"]);
        }

        // var_dump($json["@attributes"]);
    }else{
        \model\simple\Console::println("IMPOSSIBLE DE LIRE LE FICHIER AVEC MEDIAINFO");
    }

    file_put_contents(ROOT.DS."log".DS.$hash.".xml",implode("",$output));
}
*/


?>