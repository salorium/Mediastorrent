<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 11/03/14
 * Time: 01:23
 */

namespace controller;


use core\Controller;
use model\mysql\Film;
use model\mysql\Torrentfilm;
use model\mysql\Torrents_files;
use model\mysql\Utilisateur;
use model\ocelot\Requete;
use model\rtorrent\rTorrent;
use model\simple\Allocine;
use model\simple\Mail;
use model\simple\Repertoire;
use model\simple\ChaineCaractere;
use model\simple\Torrent;


class Test extends Controller
{
    function req()
    {
        \config\Conf::$userscgi = "salorium";
        $listeTorrent = rTorrent::listeTorrent();
        $this->set('liste', $listeTorrent);
    }


    function secureMdp()
    {
        $mdp = "bonjour";
        $secretBDD = "fgjfdsjqdslkjgkfdqlgdfjg";
        $salt = "fjfjfikdfds";
        $cryptmdpClient = sha1(sha1($mdp) . sha1($salt));

        $serveurMDP = sha1($cryptmdpClient . sha1($secretBDD));


        $ddBaseMDP = sha1(sha1(sha1($mdp) . sha1($salt)) . sha1($secretBDD));
        var_dump($cryptmdpClient);
        var_dump($serveurMDP == $ddBaseMDP);
        var_dump("=====================");
//        var_dump(getallheaders());
        $verbeHTTP = $_SERVER['REQUEST_METHOD'];
        $request = $_SERVER["REQUEST_URI"];
        $crymdp = $_SERVER["HTTP_REST_PASSWORD"];
        $signature = $_SERVER["HTTP_REST_SIGNATURE"];
        $login = $_SERVER["HTTP_REST_USER"];
        $url = $verbeHTTP . $request . $login;
        $signature1 = sha1(sha1($url) . $crymdp);
        var_dump($signature == $signature1);


//        $url = ""
        var_dump("=========================================");

        var_dump($_SERVER);
        var_dump("=========================================");
        var_dump($_REQUEST);
        die();
    }

    function posta()
    {
        $this->set('post', $_REQUEST);
        $this->set('file', $_FILES);
    }

    function h5allo()
    {
        $o["typesearch"] = "movie";
        $a = new Allocine("225884", $o);
        $this->set("r", $a->retourneResMovie());
    }

    function h5allo1($code)
    {
        $o["typesearch"] = "media";
        $a = new Allocine($code, $o);
        $this->set("r", $a->getMediaInfo());
    }

    function dlDossier()
    {
        \model\simple\Download::sendFile(WEBROOT . DS . "images" . DS . "favicon.svg", "Mondossier/txt.txt");
    }

    function host()
    {
        $this->set("h", array(substr($_SERVER["HTTP_HOST"] . dirname(dirname($_SERVER["SCRIPT_NAME"])) . ($_SERVER["SCRIPT_NAME"] !== "/index.php" ? "/" : ""), 0, -1)));
        $this->set("ha", array($_SERVER["HTTP_HOST"] . dirname(dirname($_SERVER["SCRIPT_NAME"])) . ($_SERVER["SCRIPT_NAME"] !== "/index.php" ? "/" : "")));
    }

    function mediainfo()
    {
        $info = new \model\simple\Mediainfo("/home/salorium/Musique/Ahzee Born Again (320)/1.Born Again (Radio Edit).mp3");
        //var_dump( $info->general);
        //die();
        //file_put_contents('test.jpg',base64_decode($info->general["Cover_Data"]));
        $this->set(array(
            "image" => base64_decode($info->general["Cover_Data"])
        ));//*/
        //var_dump();
    }

    function sm1()
    {
        $rest = \model\simple\Console::executeBrut('tmux new-session -s rt' . uniqid() . ' -n fec -d ffmpeg -i "/home/salorium/rtorrent/data/Da Tweekaz - Wodka [FLAC]/01 - Da Tweekaz - Wodka (Extended Mix).flac" -c:a libmp3lame -b:a 320k -listen 1 -vn -movflags faststart -f mp3 http://localhost:1234');
        var_dump($rest);
    }

    function sm2()
    {
        $rest = \model\simple\Console::executeBrut('tmux new-session -s rt' . uniqid() . ' -n fec -d ffmpeg -i "/home/salorium/rtorrent/data/VA-DJ_Networx_Vol._63-2CD-FLAC-2015-VOLDiES/101-hardwell_feat._chris_jones-young_again_(extended_mix).flac" -acodec libvorbis -aq 10 -listen 1 -vn -movflags faststart -f ogg http://localhost:1234');
        var_dump($rest);
    }

    function sm4()
    {
        $rest = \model\simple\Console::executeBrut('tmux new-session -s rt' . uniqid() . ' -n fec -d ffmpeg -i "/home/salorium/rtorrent/data/Kendji Girac - Kendji (2014) FLAC/(01) Color Gitano.flac" -c:a libmp3lame -b:a 320k -listen 1 -vn -movflags faststart -f mp3 http://localhost:1234');
        var_dump($rest);
    }

    function musique()
    {
        //  $rest = \model\simple\Console::executeBrut('tmux new-session -s rt -n fec -d ffmpeg -i "/home/salorium/rtorrent/data/Da Tweekaz - Wodka [FLAC]/01 - Da Tweekaz - Wodka (Extended Mix).flac" -c:a libmp3lame -b:a 320k -listen 1 -vn -movflags faststart -f mp3 http://localhost:1234');
        //var_dump($rest);
    }

    function musique1()
    {
//        $rest = \model\simple\Console::executeBrut('tmux new-session -s rt -n fec -d ffmpeg -i "/home/salorium/rtorrent/data/VA-DJ_Networx_Vol._63-2CD-FLAC-2015-VOLDiES/101-hardwell_feat._chris_jones-young_again_(extended_mix).flac" -c:a libmp3lame -b:a 320k -listen 1 -vn -movflags faststart -f mp3 http://localhost:1234');
        //var_dump($rest);
    }

    function h($t)
    {
        var_dump(strtotime($t));
    }

    function stat2($what = "up", $step = 1, $start = "-1days", $end = "now")
    {

        if (strtotime($start) !== false)
            $start = strtotime($start);
        if (strtotime($end) !== false)
            $end = strtotime($end);

        $rrdFile = DS . "home" . DS . "salorium" . DS . "rtorrent" . DS . "stat15";
        $outputPngFile = ROOT . DS . "cache" . DS . "stat1.png";
        $rrdFile1 = $rrdFile . "-1.rrd";
        $rrdFile2 = $rrdFile . "-2.rrd";
        $graphObj = new \RRDGraph($outputPngFile);
        $options = array(
            "--title" => "Rtorrent de salorium",
            "--font" => "DEFAULT:7:",
            "--watermark" => date("D j F Y G:i"),
            "--start" => $start,
            "--end" => $end,
            "--base" => 1024,
            "--width" => "1000",
            "--height" => "400",
            "--step" => $step,
            "--imgformat" => "SVG",
            //"--end"=>"-10hours",
            //"--upper-limit"=> "10485790",
            //"--lower-limit"=> "-10000000",
            //"--alt-autoscale",
            //"--alt-y-grid",
            //"--rigid",
            //"--y-grid"=>(1024*1024*10).":5",
            "--vertical-label" => "Octet",
            "DEF:myspeed=$rrdFile1:bpup:AVERAGE",
            "DEF:myspeed1=$rrdFile1:bpdown:AVERAGE",
            "DEF:myspeed2=$rrdFile2:up:AVERAGE",
            "DEF:myspeed3=$rrdFile2:down:AVERAGE",
            "DEF:myspeed4=$rrdFile2:up1:LAST",
            "DEF:myspeed5=$rrdFile2:down1:AVERAGE",
            //"CDEF:abs=myspeed3,STEPWIDTH,*,PREF,ADDNAN",
            //"CDEF:ds0modified=TIME",
            "CDEF:realspeed=myspeed,1,*",
            "CDEF:realspeed1=myspeed1," . (1) . ",*",
            "CDEF:realspeed2=myspeed2," . ($step) . ",*",
            "CDEF:realspeed3=myspeed3," . ($step) . ",*",
            "HRULE:0#000000"


        );
        switch ($what) {
            case 'up':
                $options[] = "AREA:realspeed2#CDFF66:Upload";
                //if ( $step == 1)
                $options[] = "LINE1:realspeed#00FF00:Vitesse de upload o/s";
                $options[] = 'GPRINT:myspeed4:LAST:Total Upload \: %.2lf %s';
                break;
            case 'down':
            default:
                $options[] = "AREA:realspeed3#66CDFF:Download";
                //if ( $step == 1)
                $options[] = "LINE1:realspeed1#0000FF:Vitesse de download o/s";
                $options[] = 'GPRINT:myspeed5:LAST:Total Download \: %.2lf %s';

                break;
        }
        $graphObj->setOptions(
            $options
        );
        $graphObj->save();
        header("Content-Type: " . mime_content_type($outputPngFile) . ";");
        header("Content-Length: " . filesize($outputPngFile));
        //header('Content-Disposition: attachment; filename*=UTF-8\'\'' . rawurlencode((str_replace("&lt;", "<", "dd"))) . '.' . pathinfo($outputPngFile, PATHINFO_EXTENSION));
        //header('Accept-Ranges: bytes');
        readfile($outputPngFile);

        exit();
    }

    function stat1()
    {

        $rrdFile = DS . "home" . DS . "salorium" . DS . "rtorrent" . DS . "stat9";
        $outputPngFile = ROOT . DS . "cache" . DS . "stat1.png";
        $rrdFile1 = $rrdFile . "-1.rrd";
        $rrdFile2 = $rrdFile . "-2.rrd";
        $graphObj = new \RRDGraph($outputPngFile);
        $graphObj->setOptions(
            array(
                "--title" => "Rtorrent de salorium",
                "--font" => "DEFAULT:7:",
                "--watermark" => date("D j F Y G:i"),
                "--start" => "-1hours",
                "--base" => 1024,
                "--width" => "1000",
                "--height" => "300",
                //"--upper-limit"=> "10485790",
                //"--lower-limit"=> "-10000000",
                "--vertical-label" => "Vitesse octet/s",
                "DEF:myspeed=$rrdFile1:bpup:LAST",
                "DEF:myspeed1=$rrdFile1:bpdown:LAST",
                "DEF:myspeed2=$rrdFile2:up:LAST",
                "DEF:myspeed3=$rrdFile2:down:LAST",
                "CDEF:realspeed=myspeed,1,*",
                "CDEF:realspeed1=myspeed1,-1,*",
                "CDEF:realspeed2=myspeed2,1,*",
                "CDEF:realspeed3=myspeed3,-1,*",
                "HRULE:0#000000",
                //"LINE:realspeed1#0000FF:Vitess de download",
                "AREA:realspeed2#AAFF55:Upload",
                //  "AREA:realspeed#00FF00BB:Vitesse de upload",

                "LINE:realspeed#00FF00:Vitesse de upload"

                //      "LINE:realspeed3#AA55FF:Download"
            )
        );
        $graphObj->save();
        header("Content-Type: " . mime_content_type($outputPngFile) . ";");
        header("Content-Length: " . filesize($outputPngFile));
        //header('Content-Disposition: attachment; filename*=UTF-8\'\'' . rawurlencode((str_replace("&lt;", "<", "dd"))) . '.' . pathinfo($outputPngFile, PATHINFO_EXTENSION));
        //header('Accept-Ranges: bytes');
        readfile($outputPngFile);

        exit();
    }

    function stat()
    {

        $rrdFile = DS . "home" . DS . "salorium" . DS . "rtorrent" . DS . "stat9";
        $outputPngFile = ROOT . DS . "cache" . DS . "stat1.png";
        $rrdFile1 = $rrdFile . "-1.rrd";
        $rrdFile2 = $rrdFile . "-2.rrd";
        $graphObj = new \RRDGraph($outputPngFile);
        $graphObj->setOptions(
            array(
                "--title" => "Rtorrent de salorium",
                "--font" => "DEFAULT:7:",
                "--watermark" => date("D j F Y G:i"),
                "--start" => "-1days",
                "--base" => 1024,
                "--width" => "1200",
                "--height" => "300",
                "--step" => 60 * 60,
                //"--upper-limit"=> "10485790",
                //"--lower-limit"=> "-10000000",
                "--vertical-label" => "Vitesse octet/s",
                "DEF:myspeed=$rrdFile1:bpup:AVERAGE",
                "DEF:myspeed1=$rrdFile1:bpdown:AVERAGE",
                //   "DEF:myspeed2=$rrdFile2:up:LAST",
                "DEF:myspeed3=$rrdFile2:down:LAST",
                "CDEF:realspeed=myspeed,1,*",
                "CDEF:realspeed1=myspeed1,-1,*",
                //    "CDEF:realspeed2=myspeed2,60,*",
                "CDEF:realspeed3=myspeed3,-60,*",
                "HRULE:0#000000",
                "AREA:realspeed#00FF00",
                "AREA:realspeed1#0000FF",
                "LINE:realspeed#00FF00:Vitesse de upload",
                "LINE:realspeed1#0000FF:Vitess de download",
                //      "LINE:realspeed2#AAFF55:Upload",
                //      "LINE:realspeed3#AA55FF:Download"
            )
        );
        $graphObj->save();
        header("Content-Type: " . mime_content_type($outputPngFile) . ";");
        header("Content-Length: " . filesize($outputPngFile));
        //header('Content-Disposition: attachment; filename*=UTF-8\'\'' . rawurlencode((str_replace("&lt;", "<", "dd"))) . '.' . pathinfo($outputPngFile, PATHINFO_EXTENSION));
        //header('Accept-Ranges: bytes');
        readfile($outputPngFile);

        exit();
    }
    function f()
    {
        $k = "debug";
        //var_dump(\config\Conf::"$k");

        $this->set("res", \model\simple\MakerConf::maker());
    }
    function tconf()
    {
        \config\Conf::$numerorole["test"] = 1;
        \config\Conf::$numerorole["test2"] = array("Install", "Visiteur", "Normal", "Torrent", "Sysop", "test" => 0.1);
        var_dump(\model\simple\MakerConf::makeParam(\config\Conf::$numerorole));
        var_dump(\config\Conf::$numerorole["test2"]);
        var_dump(var_export(null, true));
    }

    function sw()
    {

    }

    function filesize()
    {

    }

    function c()
    {
        var_dump(base64_decode("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r=CFe12NAfA3R6z4k0z6f462d177997d5212c13e7b73846fa8a"));

    }

    function steam()
    {
        /*
         * $content = file_get_contents("http://steamcommunity.com/id/salorium/wishlist/");
//echo preg_match_all('#<div class="wishlistRow sortableRow" id="game_([0-9]+)">#ig',$content,$matches);
//echo ($content);
$i = preg_match_all('#<div class="wishlistRow " id="game_([0-9]+)">#i',$content,$matches);
if ($i > 0)
if ( ! isset ($_REQUEST["id"]))
    $_REQUEST["id"]="252750,325520";
$steamid = explode(",",$_REQUEST["id"]);
if ($i > 0)
    $steamid = $matches[1];
//echo "<pre>";
foreach ($steamid as $k=>$v){
    $content = file_get_contents("http://store.steampowered.com/api/appdetails?appids=".$v."&cc=fr");

    $json = json_decode($content);
    //var_dump($json->$v->data);
    $plateforme = "";
    $plateforme .= ($json->$v->data->platforms->linux ? '<img width="50" src="iconmonstr-linux-os-icon.svg"/>':"");
    $plateforme .= ($json->$v->data->platforms->windows ? '<img width="50" src="iconmonstr-windows-os-icon.svg"/>':"");
    $plateforme .= ($json->$v->data->platforms->mac ? '<img width="50" src="iconmonstr-apple-os-icon.svg"/>':"");
    ?>
    <div style="float: left;width: 488px;">
    <fieldset >
        <legend><?php echo $json->$v->data->name;?></legend>
        <img src="<?php echo $json->$v->data->header_image; ?>" />

        <table>
            <tbody>
            <tr><td><?php echo $v?></td></tr>
            <tr><td> Prix : <?php echo $json->$v->data->price_overview->final /100;?> € <?php echo ($json->$v->data->price_overview->discount_percent > 0 ? '<span style="color: green;">- '.$json->$v->data->price_overview->discount_percent.' % ('.($json->$v->data->price_overview->initial/100).' €)</span>':"") ?></td></tr>
            <tr><td><?php echo $plateforme?></td></tr>
            </tbody>
        </table>
    </fieldset>
    </div>
<?php
}

  //  echo 'bonjour';

//echo "</pre>";

?>

         */
        $jeux = array();
        $content = file_get_contents("http://steamcommunity.com/id/salorium/wishlist/");
        $i = preg_match_all('#<div class="wishlistRow " id="game_([0-9]+)">#i', $content, $matches);
        if (!isset ($_REQUEST["id"]))
            $_REQUEST["id"] = "252750,325520";
        $steamid = explode(",", $_REQUEST["id"]);
        if ($i > 0)
            $steamid = $matches[1];
        foreach ($steamid as $k => $v) {
            $content = file_get_contents("http://store.steampowered.com/api/appdetails?appids=" . $v . "&cc=fr");

            $json = json_decode($content);
            //var_dump($json->$v->data);
            $jeux[] = $json->$v->data;

        }
        $this->set("jeux", $jeux);


    }
    function mysql($v)
    {
        var_dump(\core\Mysqli::real_escape_string_html($v));
    }

    function getTimeSerie()
    {
        $this->set("ffd", "ffdfd");
    }
    function gget()
    {
        var_dump($_GET);
    }
    function brantest()
    {
//exemple
    }
    function linux()
    {

    }
    function trackerUptorrent($user)
    {
        \core\Mysqli::$default = "gazelle";
        $u = Utilisateur::getUtilisteur($user);
        if ($u) {
            \config\Conf::$torrentpass = $u->torrentpass;
            if (isset($_FILES["torrent"])) {
                if ($_FILES["torrent"]["error"] > 0) {

                } else {
                    $torrent = new Torrent(file_get_contents($_FILES["torrent"]["tmp_name"]));
                    if (!$torrent->errors()) {
                        $torrent->is_private(true);
                        $torrent->announce("");
                        $hash = pack("H*", $torrent->hash_info());
                        $a = \model\mysql\Torrents::insertTorrent($hash, $torrent->__toString());
                        if (!is_bool($a)) {
                            $az = \model\ocelot\Requete::addTorrent($a, $hash, "0");
                        }
                    }
                }


            }
        } else {
            throw new \Exception("Pas d'utilisateur");
        }
    }

    function getTorrent($user = null)
    {
        \core\Mysqli::$default = "gazelle";
        \config\Conf::$torrentpass = $user;
        if (is_null($user)) {
            $user = ChaineCaractere::random(5);
            $pass = ChaineCaractere::random(32);
            \config\Conf::$torrentpass = $pass;
            Requete::addUser($user, $pass, "1");
        }

        $a = Torrents_files::getFile(12);
        $to = new Torrent($a->file);
        $to->announce(Torrent::getAnnounceUser());
        $to->send();
    }

    function wbb()
    {
        $enjson = null;
        $frjson = null;
        $xml = simplexml_load_file(ROOT . DS . "cache" . DS . "en.xml");
        $en = json_decode(json_encode($xml), true);
        $xml = simplexml_load_file(ROOT . DS . "cache" . DS . "WoltLab.Burning.Board.4.0.5-French.xml");
        $fr = json_decode(json_encode($xml), true);
        foreach ($en["category"] as $k => $v) {
            //var_dump($v["@attributes"]["name"]);
            $item = null;
            foreach ($v["item"] as $kk => $vv) {
                $enjson[$v["@attributes"]["name"]][$vv["@attributes"]["name"]] = $vv["@attributes"]["name"];

            }
            //var_dump($item);
            // = $item;
            //var_dump($v["item"]);
            //die();
        }
        foreach ($fr["category"] as $k => $v) {
            //var_dump($v["@attributes"]["name"]);
            $item = null;
            foreach ($v["item"] as $kk => $vv) {
                $frjson[$v["@attributes"]["name"]][$vv["@attributes"]["name"]] = $vv["@attributes"]["name"];

            }

            //var_dump($v["item"]);
            //die();
        }
        //var_dump($frjson["wbb.acp.board"]);
        //var_dump($frjson);
        foreach ($enjson as $k => $v) {
            if (isset($frjson[$k])) {
                //var_dump($frjson[$k]);
                echo "=" . $k . "<br>";
                foreach ($v as $kk => $vv) {
                    if (isset($frjson[$k][$vv])) {

                    } else {
                        echo "[" . $k . "]" . $kk . "<br>";
                    }
                }
            } else {
                echo "<span style='color: red'>" . $k . "</span><br>";
            }
            //die();
        }
        //var_dump(($en["category"][0]["@attributes"]["name"]));
        //var_dump(($fr->category));
        die();

    }

    function iti()
    {
        if (file_exists(ROOT . DS . "cache" . DS . "7dtds3.json")) {

        } else {
            //Créer la playliste pour la saison 3
            $tab = array();
            $tab1 = array();
            $tab2 = array();
            $tab3 = array();
            $tab4 = array();
            for ($i = 5; $i > 0; $i--) {
                $c = file_get_contents("https://api.dailymotion.com/videos?fields=id,thumbnail_480_url%2Ctitle%2C&owners=x4ak7b&limit=100&page=" . $i);
                //var_dump($c);
                $c = json_decode($c);
                //var_dump($c);
                $taille = count($c->list) - 1;
                for ($taille = $taille; $taille > 0; $taille--) {
                    //echo $v->title;
                    $v = $c->list[$taille];
                    if (preg_match_all("#^7 Days to die - S3#i", $v->title) == 1) {
                        $tab[] = $v;
                    }
                    if (preg_match_all("#^7 Days to die - S2#i", $v->title) == 1) {
                        $tab2[] = $v;
                    }
                    if (preg_match_all("#^7 Days to die - S1#i", $v->title) == 1) {
                        $tab3[] = $v;
                    }
                    if (preg_match_all("#^life is strange#i", $v->title) == 1) {
                        $tab4[] = $v;
                    }
                    if (preg_match_all("#^The Witcher 3 Wild Hunt#i", $v->title) == 1) {
                        $tab1[] = $v;
                    }
                }
            }

        }
        //die();
        $c = file_get_contents("https://api.dailymotion.com/videos?fields=id,thumbnail_480_url%2Ctitle%2C&owners=x4ak7b&limit=100");
        $this->set("data", json_decode($c));
        $c = file_get_contents("https://api.dailymotion.com/videos?fields=id,thumbnail_480_url%2Ctitle%2C&owners=x8yhwu,x5if3,xi4txd&limit=100");
        $this->set("dataamis", json_decode($c));
        $this->set("dtd3", $tab);
        $this->set("dtd1", $tab3);
        $this->set("dtd2", $tab2);
        $this->set("lis", $tab4);
        $this->set("t1", $tab1);
    }
    function ca()
    {
        date_default_timezone_set("UTC");
        echo "UTC:" . time();
        echo "<br>";

        date_default_timezone_set("Europe/Helsinki");
        echo "Europe/Helsinki:" . time();
        echo "<br>";
        $erre = false;
        try {
            //throw new \Exception("dd");
        } catch (\Exception $e) {
            $erre = true;
        }
        var_dump(\date_timezone_get());
        die();
    }

    function infofichier()
    {

        if (isset ($_FILES ['torrentfile'])) {
            if (is_array($_FILES['torrentfile']['name'])) {

                for ($i = 0; $i < count($_FILES['torrentfile']['name']); ++$i) {
                    $files[] = array
                    (
                        'name' => $_FILES['torrentfile']['name'][$i],
                        'tmp_name' => $_FILES['torrentfile']['tmp_name'][$i],
                        'error' => $_FILES ['torrentfile'] ['error'][$i]
                    );
                }

            } else {
                $files[] = $_FILES['torrentfile'];

            }
            $torrents = null;
            foreach ($files as $file) {
                $torrent = null;
                $torrent['erreur'] = -1;
                $torrent['nom'] = $file["name"];
                if (pathinfo($file["name"], PATHINFO_EXTENSION) != "torrent")
                    $file["name"] .= ".torrent";
                $des = DS . "tmp" . DS . $file["name"];
                $torrent['nom'] = $file["name"];
                $ok = move_uploaded_file($file['tmp_name'], $des);
                if ($ok) {
                    $to = new \model\simple\Torrent($des);
                    //$torrents[]= array($to->getFileName(),$to->info["name"]);
                    if ($to->errors()) {
                        $torrent['status'] = "ErreurFichier";
                    } else {
                        $info = $to->info;
                        $f = null;
                        $torrent['hash'] = $to->hash_info();
                        if (isset ($info ['files'])) {
                            $numfile = 0;
                            foreach ($info ['files'] as $key => $tfile) {
                                $nom = $info ['name'] . DS . implode(DS, $tfile ['path']);
                                if (in_array(strtolower(pathinfo($nom, PATHINFO_EXTENSION)), \config\Conf::$videoExtensions)) {
                                    $torrent["erreur"] = 0;
                                    $fi ["nom"] = $nom;
                                    $fi ["numfile"] = $numfile;
                                    $torrent['type'] = "movie";
                                    $f [] = $fi;
                                } else if (in_array(strtolower(pathinfo($nom, PATHINFO_EXTENSION)), \config\Conf::$musicExtensions)) {
                                    $torrent["erreur"] = 0;
                                    $fi ["nom"] = $nom;
                                    $fi ["numfile"] = $numfile;
                                    $torrent['type'] = "music";
                                    $f [] = $fi;
                                }
                                $numfile++;
                            }
                        } else if (in_array(strtolower(pathinfo($info ['name'], PATHINFO_EXTENSION)), \config\Conf::$videoExtensions)) {
                            $torrent["erreur"] = 0;
                            $fi ["nom"] = $info ['name'];
                            $fi["numfile"] = 0;
                            //$fi ["ext"] = pathinfo ( $info ['name'], PATHINFO_EXTENSION );
                            $torrent['type'] = "movie";
                            //$fi ["nomaff"] = formatNomAff ( $fi ["nom"] );
                            $f [] = $fi;
                        } else if (in_array(strtolower(pathinfo($info ['name'], PATHINFO_EXTENSION)), \config\Conf::$musicExtensions)) {
                            $torrent["erreur"] = 0;
                            $fi["numfile"] = 0;
                            $fi ["nom"] = $info ['name'];
                            //$fi ["ext"] = pathinfo ( $info ['name'], PATHINFO_EXTENSION );
                            $torrent['type'] = "music";
                            //$fi ["nomaff"] = formatNomAff ( $fi ["nom"] );
                            $f [] = $fi;
                        }
                        if (is_null($f)) {
                            $torrent["status"] = "Aucun fichier compatible avec la bibliothèque (" . /* Thumbnailers::getStringExtension () .*/
                                ")";
                        } else {
                            $torrent["files"] = $f;
                        }
                    }
                    unlink($des);
                }
                $torrents[] = $torrent;
            }
            /*$tor = null;
            foreach( $files as $file )
            {
                $ufile = $file['name'];
                if(pathinfo($ufile,PATHINFO_EXTENSION)!="torrent")
                    $ufile.=".torrent";
                $nomm = md5(uniqid(rand(), true));
                $to = null;
                $to["name"]=$file['name'];
                $to["erreur"] = -1;
                $ok = move_uploaded_file($file['tmp_name'],"/home/admin/salorium/log/".$nomm.".torrent");
                if ($ok ){
                    $torrent = new Torrent ( "/home/admin/salorium/log/".$nomm.".torrent" );
                    if ($torrent->errors ()) {
                        $to["status"] = "FailedFile";
                    }else{

                        $info = $torrent->info;
                        $f = null;

                        if (isset ( $info ['files'] )){
                            foreach ( $info ['files'] as $key => $tfile ) {
                                $nom = $topDirectory.$info ['name']."/" .implode ( '/', $tfile ['path'] );
                                if (in_array ( strtolower ( pathinfo ( $nom, PATHINFO_EXTENSION ) ), Thumbnailers::$videoExtensions )) {
                                    $to["erreur"] = 0;
                                    $fi ["nom"] = basename($nom);
                                    $fi ["ext"] = pathinfo ( $nom, PATHINFO_EXTENSION );
                                    $fi ["nomaff"] = formatNomAff ( $fi ["nom"] );
                                    $f [] = $fi;
                                }
                            }
                        }
                        else if (in_array ( strtolower ( pathinfo ( $info ['name'], PATHINFO_EXTENSION ) ), Thumbnailers::$videoExtensions )) {
                            $to["erreur"] = 0;
                            $fi ["nom"] = basename($topDirectory.$info ['name']."/".$info ['name']);
                            $fi ["ext"] = pathinfo ( $info ['name'], PATHINFO_EXTENSION );
                            $fi ["nomaff"] = formatNomAff ( $fi ["nom"] );
                            $f [] = $fi;
                        }
                        if (is_null($f)){
                            $to["status"] = "Aucun fichier compatible avec le site (" . Thumbnailers::getStringExtension () . ")";
                        }else{
                            $to["file"] = $f;
                        }

                    }
                    unlink("/home/admin/salorium/log/".$nomm.".torrent");

                }else{
                    $to["status"] = "Erreur de déplacement ou upload code erreur =>".$file['error'];
                }
                $tor[]=$to;
            }
            $t ["torrent"] = $tor;
            $t ["rep"] = parCourBdd ( 0, "/" );
            $j ["data"] = $t;*/
        } else {
            /*$j['status'] = "NoFichier";
            $j['erreur'] = -1;*/
        }
        $this->set(array(
            "post" => $_POST,
            "file" => $_FILES,
            "torrent" => $torrents
        ));
    }

    function post()
    {
        $this->set(array(
            "post" => $_POST,
            "file" => $_FILES
        ));
    }

    function get()
    {
        $this->set(array(
            "post" => $_POST,
            "file" => $_FILES,
            "SCRIPT_NAME" => $_SERVER["SCRIPT_NAME"],
            "DOCUMENT_ROOT" => $_SERVER["DOCUMENT_ROOT"],
            "PATH_INFO" => $_SERVER["PATH_INFO"]
        ));
    }

    function delT()
    {
        //Torrentfilm::deleteByClefunique("43NW5URHgH");
    }

    function getT()
    {
        $to = \core\Memcached::value("salorium", "torrentfile1404853105");
        $tott = new Torrent($to);
        $tott->send();
    }

    function tfind()
    {
        $vv = Repertoire::getFindAll();
        $this->set("rep", $vv);
    }

    function ct()
    {
        $path_edit = "/home/salorium/rtorrent/data/Alaska.La.ruee.vers.l.or.S04E03.avi";
        $piece_size = "512";
        $callback_log = create_function('$msg', 'echo $msg');
        $callback_err = create_function('$msg', 'echo $msg;');


        $torrent = new \model\simple\Torrent($path_edit, array(), $piece_size, $callback_log, $callback_err);
        $torrent->is_private(true);
        var_dump($torrent->info['name']);
        var_dump($torrent);
    }

    function rt($ports)
    {
        \config\Conf::$userscgi = $ports;
        var_dump(\model\xmlrpc\rTorrentSettings::get(\config\Conf::$userscgi, true));
    }

    function addFilm($id)
    {
        $o["typesearch"] = "movie";
        $allo = new \model\simple\Allocine($id, $o);
        $infos = $allo->retourneResMovieFormatForBD();
        $genre = $infos["Genre"];
        $infos["Genre"] = implode(", ", $genre);
        $titre = (isset($infos["Titre"]) ? $infos["Titre"] : $infos["Titre original"]);
        $otitre = $infos["Titre original"];
        $urlposter = "";
        $urlbackdrop = "";
        $realisateurs = $infos["Réalisateur(s)"];
        $acteurs = "";
        if (isset($infos["Acteur(s)"]))
            $acteurs = $infos["Acteur(s)"];
        $anneeprod = $infos["Année de production"];
        $film = \model\mysql\Film::ajouteFilm($titre, $otitre, json_encode($infos), $urlposter, $urlbackdrop, $anneeprod, $acteurs, $realisateurs, $id);
        $film->addGenre($genre);
        $film->addGenre("Comédiatation");
    }

    function isql()
    {
        $querys = file_get_contents(ROOT . DS . "mysql" . DS . "mediastorrent.sql");
        $t = \core\Mysqli::multiquery($querys);
        \core\Mysqli::getObjectAndClose(false);
    }

    function portrtorrent($userscgi)
    {
        //\config\Conf::$portscgi = $portscgi;
        $this->set("rtorrent", rTorrentSettings::get($userscgi)->port);
    }

    function tfilm()
    {
        \model\mysql\Torrentfilm::rechercheParNumFileHashClefunique(0, 'FA0C487D79DD07DB1BE85E9639D9E5B112DD39EE', '9JkOBaF1Hs');
        //\model\mysql\Torrentfilm::addTorrentFilm("wHOXNvBDDy", "0", "ddd", "salorium", "BigTerra2", "dd", "a", false);
    }

    function clefunique()
    {
        echo "FIN => " . \model\mysql\Torrentfilm::getClefUnique();
    }
    /*function addFilm(){
        $f = Film::ajouteFilm("Titi","Titi","az","az","dd");
        echo $f->id;
        die();
    }*/
    /*function mailo($login,$mail,$mdp){
        $this->set(array(
            "mail"=>Mail::activationMotDePasse($mdp,$login,$mail)
        ));
    }*/
    function mail($mail)
    {
        // Plusieurs destinataires
        $to = "" . $mail . "";

        // Sujet
        $subject = 'Calendrier des anniversaires pour Août';

        // message
        $message = '
     <html>
      <head>
       <title>Calendrier des anniversaires pour Août</title>
      </head>
      <body>
       <p>Voici les anniversaires à venir au mois d\'Août !</p>
       <table>
        <tr>
         <th>Personne</th><th>Jour</th><th>Mois</th><th>Année</th>
        </tr>
        <tr>
         <td>Josiane</td><td>3</td><td>Août</td><td>1970</td>
        </tr>
        <tr>
         <td>Emma</td><td>26</td><td>Août</td><td>1973</td>
        </tr>
       </table>
      </body>
     </html>
     ';

        // Pour envoyer un mail HTML, l'en-tête Content-type doit être défini
        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        // En-têtes additionnels
        // $headers .= 'To: Mary <mary@example.com>, Kelly <kelly@example.com>' . "\r\n";
        $headers .= 'From: admin@' . $_SERVER["HTTP_HOST"] . '' . "\r\n";
        //$headers .= 'Cc: anniversaire_archive@example.com' . "\r\n";
        //$headers .= 'Bcc: anniversaire_verif@example.com' . "\r\n";

        // Envoi
        $this->set(array(
            "mail" => mail($to, $subject, $message, $headers)
        ));
    }

    function allocine($re)
    {
        $o["typesearch"] = "movie";
        $all = new \model\simple\Allocine($re, $o);
        $this->set(array(
            "film" => $all->retourneResMovie()
        ));
    }

    function iso3166()
    {
        \model\simple\Iso31::getIso3166();
    }

    function tmdb($re)
    {
        $tmdb = new \model\simple\TheMovieDb();
        $this->set(array(
            "film" => $tmdb->getMovieFormat($re)
        ));
    }

    function accerole($role)
    {
        $num = \config\Conf::$rolenumero[$role];
        $compteurarray = null;
        $tass = null;
        do {
            $role = \config\Conf::$numerorole[$num];
            if (is_array($role)) {
                if (is_null($compteurarray)) {
                    $compteurarray = 0;
                }
                $r = $role;
                $role = $role[$compteurarray];
                $compteurarray++;
                if ($compteurarray == count($r)) {
                    $compteurarray = null;
                    $num--;
                }

            } else {
                $num--;
            }

            if ($role === \config\Conf::$numerorole[0]) {
                $repertoire = ROOT . DS . "controller";
            } else {
                $repertoire = ROOT . DS . "controller" . DS . \strtolower($role);

            }


            if (\file_exists($repertoire)) {
                $MyDirectory = \opendir($repertoire);
                while ($Entry = @\readdir($MyDirectory)) {
                    if (!is_dir($repertoire . DS . $Entry) && $Entry != '.' && $Entry != '..') {
                        if ($role === \config\Conf::$numerorole[0]) {
                            $cname = '\controller\\' . pathinfo($Entry, PATHINFO_FILENAME);
                        } else {
                            $cname = '\controller\\' . strtolower($role) . '\\' . pathinfo($Entry, PATHINFO_FILENAME);
                        }
                        $c = new $cname($this->request, $this->debug);
                        $cn = explode("\\", $cname);
                        $cn = $cn[count($cn) - 1];
                        $v = get_class_methods($c);
                        if (!isset ($tass[$cn]))
                            $tass[$cn] = array();
                        foreach ($v as $k => $vv) {
                            if (!in_array($vv, $tass[$cn]))
                                $tass[$cn][] = $vv;
                        }
                    }

                }
                \closedir($MyDirectory);

            }
        } while ($num > -1);
        $this->set(array(
            "droits" => $tass
        ));
    }

    function getAllMemcached()
    {
        $n = \core\Memcached::getInstance();
        //var_dump($n->getAllKeys());
        $tab = null;
        $a = $n->getAllKeys();
        foreach ($a as $k => $v) {
            $tab[$v] = $n->get($v);
        }
        //\core\Memcached::value(\config\Conf::$user["user"]->login,"user");
        // \core\Memcached::value(\config\Conf::$user["user"]->login,"user");

        $this->set(array(
            "memcached" => $tab
        ));
    }

    function genereCache()
    {
        for ($i = 0; $i < 100; $i++) {
            $login = \model\simple\ChaineCaractere::random(5);
            \core\Memcached::value($login, "user", \model\simple\ChaineCaractere::random(105, true), 60 * 60);
        }

    }

    function xmlrpcrxmlrpcrequestall()
    {
        $cmds = array(
            "d.get_hash=", "d.is_open=", "d.is_hash_checking=", "d.is_hash_checked=", "d.get_state=",
            "d.get_name=", "d.get_size_bytes=", "d.get_completed_chunks=", "d.get_size_chunks=", "d.get_bytes_done=",
            "d.get_up_total=", "d.get_ratio=", "d.get_up_rate=", "d.get_down_rate=", "d.get_chunk_size=",
            "d.get_custom1=", "d.get_peers_accounted=", "d.get_peers_not_connected=", "d.get_peers_connected=", "d.get_peers_complete=",
            "d.get_left_bytes=", "d.get_priority=", "d.get_state_changed=", "d.get_skip_total=", "d.get_hashing=",
            "d.get_chunks_hashed=", "d.get_base_path=", "d.get_creation_date=", "d.get_tracker_focus=", "d.is_active=",
            "d.get_message=", "d.get_custom2=", "d.get_free_diskspace=", "d.is_private=", "d.is_multi_file=", "d.get_throttle_name=", "d.get_custom=chk-state",
            "d.get_custom=chk-time", "d.get_custom=sch_ignore", 'cat="$t.multicall=d.get_hash=,t.get_scrape_complete=,cat={#}"', 'cat="$t.multicall=d.get_hash=,t.get_scrape_incomplete=,cat={#}"',
            'cat=$d.views=', "d.get_custom=seedingtime", "d.get_custom=addtime"
        );
        $cmd = new \model\xmlrpc\rXMLRPCCommand("d.multicall", "main");
        $cmd->addParameters(array_map("\\model\\xmlrpc\\rTorrentSettings::getCmd", $cmds));
        $cnt = count($cmd->params) - 1;
        $req = new \model\xmlrpc\rXMLRPCRequest(5001, $cmd);
        $t = null;
        if ($req->success()) {
            $i = 0;
            $tmp = array();
            $status = array('started' => 1, 'paused' => 2, 'checking' => 4, 'hashing' => 8, 'error' => 16);

            while ($i < count($req->val)) {
                $torrent = null;
                $state = 0;
                $is_open = $req->val[$i + 1];
                $is_hash_checking = $req->val[$i + 2];
                $is_hash_checked = $req->val[$i + 3];
                $get_state = $req->val[$i + 4];
                $get_hashing = $req->val[$i + 24];
                $is_active = $req->val[$i + 29];
                $msg = $req->val[$i + 30];
                if ($is_open != 0) {
                    $state |= $status["started"];
                    if (($get_state == 0) || ($is_active == 0))
                        $state |= $status["paused"];
                }
                if ($get_hashing != 0)
                    $state |= $status["hashing"];
                if ($is_hash_checking != 0)
                    $state |= $status["checking"];
                if ($msg != "" && $msg != "Tracker: [Tried all trackers.]")
                    $state |= $status["error"];
                $torrent[] = $state; //state 0
                $torrent[] = $req->val[$i + 5]; //nom 1
                $torrent[] = $req->val[$i + 6]; //taille 2
                $get_completed_chunks = $req->val[$i + 7];
                $get_hashed_chunks = $req->val[$i + 25];
                $get_size_chunks = $req->val[$i + 8];
                $chunks_processing = ($is_hash_checking == 0) ? $get_completed_chunks : $get_hashed_chunks;
                $done = floor($chunks_processing / $get_size_chunks * 1000);
                $torrent[] = $done; // 3
                $torrent[] = $req->val[$i + 9]; //downloaded 4
                $torrent[] = $req->val[$i + 10]; //Uploaded 5
                $torrent[] = $req->val[$i + 11]; //ratio 6
                $torrent[] = $req->val[$i + 12]; //UL 7
                $torrent[] = $req->val[$i + 13]; //DL 8
                $get_chunk_size = $req->val[$i + 14];
                $torrent[] = ($req->val[$i + 13] > 0 ? floor(($get_size_chunks - $get_completed_chunks) * $get_chunk_size / $req->val[$i + 13]) : -1); //Eta 9 (Temps restant en seconde)
                /*$get_peers_not_connected = $req->val[$i+17];
                $get_peers_connected = $req->val[$i+18];
                $get_peers_all = $get_peers_not_connected+$get_peers_connected;*/
                $torrent[] = $req->val[$i + 16]; //Peer Actual 10
                $torrent[] = $req->val[$i + 19]; //Seed Actual 11
                $seeds = 0;
                foreach (explode("#", $req->val[$i + 39]) as $k => $v) {
                    $seeds += $v;
                }
                $peers = 0;
                foreach (explode("#", $req->val[$i + 40]) as $k => $v) {
                    $peers += $v;
                }
                $torrent[] = $peers; //Peer total 12
                $torrent[] = $seeds; //Seed tota 13


                $torrent[] = $req->val[$i + 20]; //Taille restant 14
                $torrent[] = $req->val[$i + 21]; //Priority 15 (0 ne pas télécharger, 1 basse, 2 moyenne, 3 haute)
                $torrent[] = $req->val[$i + 22]; //State change 16 (dernière date de change d'état)
                $torrent[] = $req->val[$i + 23]; //Skip total 17
                $torrent[] = $req->val[$i + 26]; //Base Path 18
                $torrent[] = $req->val[$i + 27]; //Date create 19
                $torrent[] = $req->val[$i + 28]; //Focus tracker 20
                /*try {
                    torrent.comment = this.getValue(values,31);
                    if(torrent.comment.search("VRS24mrker")==0)
                        torrent.comment = decodeURIComponent(torrent.comment.substr(10));
                } catch(e) { torrent.comment = ''; }*/
                $torrent[] = $req->val[$i + 32]; //Torrent free diskspace 21
                $torrent[] = $req->val[$i + 33]; //Torrent is private 22
                $torrent[] = $req->val[$i + 34]; //Torrent is multifile 23
                $torrent[] = preg_replace("#\n#", "", $req->val[$i + 42]); //Torrent seed time 24
                $torrent[] = preg_replace("#\n#", "", $req->val[$i + 43]); //Torrent add time 25
                $torrent[] = $msg; //Message tracker 26
                $torrent[] = $req->val[$i]; //Hash 27
                $tmp[$req->val[$i]] = $torrent;
                $i = $i + 44;

            }
            $data = $tmp;
            /*if (isset($_REQUEST["cid"])){
                if ($anc = MyMemcache::value("listrt".$_REQUEST["cid"])){
                    foreach ($anc as $k=>$v){
                        if (!isset($tmp[$k]))
                            $tmp[$k]=false;
                        foreach($v as $kk=>$vv){
                            if (isset($tmp[$k][$kk]) && $tmp[$k][$kk] == $vv){
                                unset($tmp[$k][$kk]);
                            }
                        }
                        if (count($tmp[$k]) ==0)
                            unset($tmp[$k]);
                    }
                }
            }

            $cid = uniqid(sha1(time()).$_COOKIE["login"]);
            if (!(MyMemcache::value("listrt".$cid,$data,60*5)))
                trigger_error("Impossible de mettre des données dans le cache");*/
            $t[] = $tmp;
            //$t[]= $cid;
            //$t[]= $_SERVER["HTTP_HOST"];
            //$t[]= disk_total_space(Variable::$documentroot."../rtorrent/data/salorium");
            //$t[]= disk_total_space(Variable::$documentroot."../rtorrent/data/salorium")-disk_free_space(Variable::$documentroot."../rtorrent/data/salorium");

            $cmds = array(
                "get_up_rate", "get_upload_rate", "get_up_total", "get_down_rate", "get_download_rate", "get_down_total"
            );
            $req = new \model\xmlrpc\rXMLRPCRequest(5001);

            foreach ($cmds as $cmd)
                $req->addCommand(new \model\xmlrpc\rXMLRPCCommand(5001, $cmd));
            if ($req->success())
                $t[] = $req->val;

        }
        if (is_null($t)) trigger_error("Impossible de se connecter à rtorrent :(");
        var_dump($t);
    }

    function ssh()
    {
        \model\simple\Ssh::supprime("salorium", "/home/salorium/test");
    }

} 