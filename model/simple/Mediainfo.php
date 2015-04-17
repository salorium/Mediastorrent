<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 03/05/14
 * Time: 03:53
 */

namespace model\simple;


class Mediainfo extends \core\Model
{
    public $mediainfo;
    public $general;
    public $videos;
    public $audios = array();
    public $texts = array();

    function __construct($file)
    {
        exec('mediainfo -f --Output=XML --BOM "' . $file . '"', $output, $error);
        if ($error) {
            \model\simple\Console::println("Impossible de charger le mediainfos");
            throw new \Exception("Impossible de charger le mediainfos");
        }
        $output=preg_replace('#_*>#','>',$output);
        file_put_contents(ROOT . DS . "log" . DS . "test1.xml", implode("", $output));
        $this->mediainfo = json_decode(json_encode(simplexml_load_string(implode("", $output))), true);
        foreach ($this->mediainfo["File"]["track"] as $v) {
            switch ($v["@attributes"]["type"]) {
                case "General":
                    $this->general = $v;
                    break;
                case "Video":
                    $this->videos = $v;
                    break;
                case "Audio":
                    $this->audios[] = $v;
                    break;
                case "Text":
                    $this->texts[] = $v;
                    break;
            }
        }
    }

    function getFormatFilm()
    {
        $res["duree"] = $this->general["Duration"][1];
        if (isset($this->videos["Codec"][1])) {
            switch ($this->videos["Codec"][1]) {
                case "AVC":
                    $res["codec"] = basename($this->videos["Internet_media_type"]);
                    break;
                default:
                    $res["codec"] = $this->videos["Codec"][1];
                    break;
            }
        }

        if (isset($this->videos["Width"])) {
            switch ($this->videos["Width"][0]) {
                case 1280:
                    $res["typequalite"] = "HD";
                    $res["qualite"] = "720";
                    break;
                case 1920:
                    $res["typequalite"] = "HD";
                    $res["qualite"] = "1080";
                    break;
                default:
                    $res["typequalite"] = "SD";
                    break;
            }
        }
        if (isset($res["qualite"])) {
            if (isset ($this->videos["Interlacement"])) {
                $res["qualite"] .= substr(strtolower($this->videos["Interlacement"][0]), 0, 1);
            }
        }
        $audios = null;
        $res["nbpisteaudios"] = 0;
        foreach ($this->audios as $v) {
            $a = null;
            if (isset ($v["Commercial_name"])) {
                if ("MPEG Audio" === $v["Commercial_name"]) {
                    $a["type"] = $v["Codec_ID_Hint"];
                } else {
                    $a["type"] = $v["Commercial_name"];
                }

            }
            if (isset($v["Channel_positions"])) {
                $canal = explode("/", $v["Channel_positions"][1]);
                $c = 0;
                foreach ($canal as $vv) {
                    $c += $vv;
                }

                $a["cannal"] = $c;
            } else if (isset ($v["Channel_count"][0])) {
                $a["cannal"] = $v["Channel_count"][0];

            }
            if (isset($v["Language"])) {
                $a["lang"] = $v["Language"][1];
            }
            $audios[] = $a;
            $res["nbpisteaudios"]++;
        }
        $res["audios"] = $audios;
        $soustitre = null;
        $res["nbsoustitre"] = 0;
        foreach ($this->texts as $v) {
            $a = null;
            if (isset($v["Title"]))
                $soustitre[] = $v["Title"];
            $res["nbsoustitre"]++;
        }
        $res["soustitre"] = $soustitre;
        return $res;
    }

}