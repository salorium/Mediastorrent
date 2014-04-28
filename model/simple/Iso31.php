<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 13/04/14
 * Time: 20:14
 */

namespace model\simple;


use core\Model;

class Iso31 extends Model {
    private $urlreference = "http://www.geonames.org/export/web-services.html#countryInfo";
    private $urldb = "http://api.geonames.org/countryInfoJSON?username=demo&lang=";
    private $pathcache;
    function __construct(){
        parent::__construct();
        $this->pathcache = ROOT.DS."cache".DS."iso3166";
        if (!file_exists($this->pathcache)){
            mkdir($this->pathcache);
        }
    }
    public function iso3166($lang="fr"){
        $path = $this->pathcache.DS."iso3166_".$lang.".json";
        if ( file_exists($path)){
            return json_decode(file_get_contents($path),true);
        }else{
            /*$ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->urlreference);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_COOKIEJAR, ROOT.DS."cache".DS."cookie.txt");
            curl_exec($ch);
            curl_close($ch);*/
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->urldb.$lang);
            curl_setopt($ch, CURLOPT_COOKIEFILE, ROOT.DS."cache".DS."cookie.txt");
            curl_setopt($ch, CURLOPT_REFERER,$this->urlreference);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
            $response = curl_exec($ch);
            curl_close($ch);
            $table = json_decode($response);
            $t = array();
            if ( isset($table->geonames)){
                foreach( $table->geonames as $k=>$v){
                    $t[$v->countryCode]= $v->countryName;
                }
                file_put_contents($path,json_encode($t));
                return $t;
            }else{
                sleep(10);
                return $this->iso3166($lang);
            }
        }
    }
    static function getIso3166($lang="fr"){
        $a = new Iso31();
       return $a->iso3166($lang);


    }

} 