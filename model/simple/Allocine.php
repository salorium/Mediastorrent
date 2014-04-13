<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 11/04/14
 * Time: 11:30
 */

namespace model\simple;


use core\Model;

class Allocine extends Model {
    private $query;
    private $option;
    public $res;
    private $baseurl = "http://api.allocine.fr/rest/v3";
    private $partenaire_key = "100043982026";
    private $secret_key = "29d185d98c984a359e6e6f26a0474269";
    public function getRandomUserAgent(){
        $v = rand(1, 9) . '.' . rand(0, 9). '.' . rand(0, 9);
        return "Dalvik/1.7.0 (Linux; U; Android $v; SGH-T989 Build/IML74KK)";
    }
    private $typesearch;
    public $maxPage=1;
    function __construct($q,$o=null) {
        $this->query = $q;


        if (is_null($o)){
            $this->typesearch = "search";
            $params = array(
                'page' => 1,
                'partner' => $this->partenaire_key,
                'q' => $this->query,
                'format' => 'json',
                'filter' => 'movie,tvseries'
            );
        }else{
            $this->typesearch = (isset($o["typesearch"]) ? $o["typesearch"]:"search");
            $params = array(
                'page' => 1,
                'partner' => $this->partenaire_key,
                'q' => $this->query,
                'format' => 'json',
                'filter' => 'movie,tvseries'
            );
            switch ($this->typesearch){

                case "search":
                    $params = array(
                        'page' => (isset($o["page"]) ? $o["page"]:"1"),
                        'partner' => $this->partenaire_key,
                        'q' => $this->query,
                        'format' => 'json',
                        'filter' => (isset($o["filter"]) ? $o["filter"]:"movie,tvseries")
                    );
                    break;
                case "movie":
                    $params = array(
                        'partner' => $this->partenaire_key,
                        'code' => $this->query,
                        'profile' => (isset($o["profile"]) ? $o["profile"]:"large"),
                        'filter' => (isset($o["filter"]) ? $o["filter"]:"movie"),
                        'striptags' => (isset($o["striptags"]) ? $o["striptags"]:"synopsis,synopsisshort"),
                        'format' => 'json',
                    );

                    $this->option .= "&profile=";
                    $this->option .= (isset($o["profile"]) ? $o["profile"]:"large");
                    $this->option .= "&striptags=";
                    $this->option .= (isset($o["striptags"]) ? $o["striptags"]:"synopsis,synopsisshort");
                    $this->option .= "&code=".$this->query;
                    break;
                case "tvseries":
                    $params = array(
                        'partner' => $this->partenaire_key,
                        'code' => $this->query,
                        'profile' => (isset($o["profile"]) ? $o["profile"]:"large"),
                        'striptags' => (isset($o["striptags"]) ? $o["striptags"]:"synopsis,synopsisshort"),
                        'format' => 'json',
                    );
                    $this->option .= "&profile=";
                    $this->option .= (isset($o["profile"]) ? $o["profile"]:"large");
                    $this->option .= "&striptags=";
                    $this->option .= (isset($o["striptags"]) ? $o["striptags"]:"synopsis,synopsisshort");
                    $this->option .= "&code=".$this->query;
                    break;
            }

        }
        $query_url = $this->baseurl.'/'.$this->typesearch;
        $sed = date('Ymd');
        $sig = urlencode(base64_encode(sha1($this->secret_key.http_build_query($params).'&sed='.$sed, true)));
        $query_url .= '?'.http_build_query($params).'&sed='.$sed.'&sig='.$sig;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $query_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_USERAGENT, $this->getRandomUserAgent());
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $response = curl_exec($ch);
        curl_close($ch);
        $contentbrut = $response;
        $contentcorrige = str_replace("$", "_", $contentbrut);
        $contentcorrige = str_replace("…",'...',$contentcorrige);
        $this->res = json_decode($contentcorrige);
        if ($this->autoriserSearch()){
            $max = -1;
            if (isset($this->res->feed->results)){
                foreach ($this->res->feed->results as $k => $v){
                    if ($max<$v->_){
                        $max = $v->_;
                    }
                }
                $this->maxPage = ceil($max/10);
            }
        }
    }
    function autoriser($type){
        return $this->typesearch === $type;
    }
    function autoriserSearch(){
        return $this->autoriser("search");
    }
    function autoriserSerie(){
        return $this->autoriser("tvseries");
    }
    function autoriserMovie(){
        return $this->autoriser("movie");
    }
    function retourneResType($type){
        if ($this->autoriserSearch())
            if (isset($this->res->feed->$type))
                return $this->res->feed->$type;
        return null;

    }
    function retourneResSeries (){
        return $this->retourneResType("tvseries");
    }
    function retourneResMovies (){
        return $this->retourneResType("movie");
    }
    function retourneResMoviesFormat (){
        $gres = null;
        $res = $this->retourneResMovies();
        if ($res != null){
            foreach ($res as $k => $v){
                $tmp["type"] = "movie";
                $tmp["code"] = $v->code;
                if (isset($v->originalTitle))
                    $tmp["originaltitre"]= $v->originalTitle;
                if (isset($v->title))
                    $tmp["titre"]= $v->title;
                if (isset($v->productionYear))
                    $tmp["anneeprod"] =$v->productionYear;
                if (isset($v->castingShort->directors))
                    $tmp["realisateur"] = $v->castingShort->directors;
                if (isset($v->castingShort->actors))
                    $tmp["acteur"] = $v->castingShort->actors;
                if (isset($v->poster->href))
                    $tmp["image"] = $v->poster->href;
                $gres[] = $tmp;
            }
        }
        return $gres;
    }
    function retourneResSeriesFormat (){
        $gres = null;
        $res = $this->retourneResSeries();
        if ($res != null){
            foreach ($res as $k => $v){
                $tmp["type"] = "tvseries";
                $tmp["code"] = $v->code;
                if (isset($v->originalTitle))
                    $tmp["originaltitre"]= $v->originalTitle;
                if (isset($v->title))
                    $tmp["titre"]= $v->title;
                if (isset($v->yearStart))
                    $tmp["anneestart"] = $v->yearStart;
                if (isset($v->castingShort->creators))
                    $tmp["realisateur"] = $v->castingShort->creators;
                if (isset($v->castingShort->actors))
                    $tmp["acteur"] = $v->castingShort->actors;
                if (isset($v->poster->href))
                    $tmp["image"] = $v->poster->href;
                $gres[] = $tmp;
            }
        }
        return $gres;
    }
    function retourneResSerie(){
        if ($this->autoriserSerie())
            if (isset($this->res->tvseries))
                return $this->res->tvseries;
        return null;

    }
    function retourneResSerieFormat(){
        $v= $this->retourneResSerie();
        if ( $v != null){
            $tmp["type"] = "tvseries";
            $tmp["code"] = $v->code;
            if (isset($v->originalTitle))
                $tmp["Titre original"]= $v->originalTitle;
            if (isset($v->title))
                $tmp["Titre"]= $v->title;
            if (isset($v->yearStart))
                $tmp["Lancement"] = $v->yearStart;
            if (isset($v->seasonCount))
                $tmp["Nombre de saison"] = $v->seasonCount;
            if (isset($v->episodeCount))
                $tmp["Nombre d'épisode"] = $v->episodeCount;
            if (isset($v->formatTime))
                $tmp["Durée"]=  $this->dateFormat($v->formatTime*60);
            if (isset($v->productionStatus->_))
                $tmp["Status"] =  $v->productionStatus->_;
            if (isset($v->originalBroadcast->dateStart))
                $tmp["Date de diffusion"] =  preg_replace("#(\d+)\-(\d+)\-(\d+)#", "$3/$2/$1",  $v->originalBroadcast->dateStart);
            if (isset($v->castingShort->creators))
                $tmp["Réalisateur"] =  $v->castingShort->creators;
            if (isset($v->castingShort->actors))
                $tmp["Acteur(s)"] =  $v->castingShort->actors;
            /*if (isset($v->poster->href))
                $tmp["image"] =  $v->poster->href;
            */
            foreach($v->media AS $k=>$vv){
                if ( $vv->class === "picture"){
                    $width=0;
                    $height=0;
                    if ( isset($vv->width) && isset($vv->height)){
                        $width = $vv->width;
                        $height = $vv->height;
                    }else{
                        $info = getimagesize ( $vv->thumbnail->href );
                        $width = $info[0];
                        $height = $info[1];
                    }
                    if ( $width > $height){
                        //Backdrop
                        $tmp["imagebackdrop"][]= array($vv->thumbnail->href,$width,$height);
                    }else{
                        //Poster
                        $tmp["imageposter"][]= array($vv->thumbnail->href,$width,$height);
                    }
                }
            }
            $tmdb = new TheMovieDb();
            $tmp1 = $tmdb->searchSerie($v->originalTitle,"en");
            if ( isset($tmp1->results)){
                $tmp1 =$tmdb->getSerieImage($tmp1->results[0]->id);
                foreach( $tmp1->backdrops as $k=>$vv){
                    //var_dump($vv);
                    //die();
                    $tmp["imagebackdrop"][]= array("http://image.tmdb.org/t/p/original".$vv->file_path,$vv->width,$vv->height);
                }
                foreach( $tmp1->posters as $k=>$vv){
                    $tmp["imageposter"][]= array("http://image.tmdb.org/t/p/original".$vv->file_path,$vv->width,$vv->height);
                }
            }

            if (isset($v->statistics->userRating))
                $tmp["Note des spectacteurs"]= $v->statistics->userRating;
            if (isset($v->statistics->pressRating))
                $tmp["Note de la presse"]=  $v->statistics->pressRating;
            if (isset($v->nationality)){
                $tmp["Origine"] = "";
                foreach ($v->nationality as $k=>$vs)
                    $tmp["Origine"] .= $vs->_.", ";
                $tmp["Origine"] = substr($tmp["Origine"],0,-2);
            }

            if (isset($v->genre)){
                $tmp["Genre"] = "";
                foreach ($v->genre as $k=>$vs)
                    $tmp["Genre"] .= $vs->_.", ";
                $tmp["Genre"] = substr($tmp["Genre"],0,-2);
            }
            if (isset($v->synopsis))
                $tmp["Synopsis"] =  $v->synopsis;
            if (isset($v->synopsisShort))
                $tmp["synopsiscourt"] =  $v->synopsisShort;
            return $tmp;
        }
        return null;

    }
    function retourneResMovie(){
        if ($this->autoriserMovie())
            if (isset($this->res->movie))
                return $this->res->movie;
        return null;

    }
    private function dateFormat($date){
        $heure = floor ($date / 3600);
        $date = $date % 3600;
        $minute = floor ($date / 60);
        $seconde = $date % 60;
        return substr(($heure != 0 ? $heure." h ":"").($minute != 0 ? $minute." min ":$minute." min ").($seconde != 0 ? $seconde." s ":""),0,-1);
    }
    function retourneResMovieFormat(){
        $v= $this->retourneResMovie();
        if ( $v != null){
            $tmp["type"] = "movie";
            $tmp["code"] = $v->code;
            if (isset($v->originalTitle))
                $tmp["Titre original"]= $v->originalTitle;
            if (isset($v->title))
                $tmp["Titre"]= $v->title;
            if (isset($v->productionYear))
                $tmp["Année de production"] = $v->productionYear;
            if (isset($v->runtime))
                $tmp["Durée"] =  $this->dateFormat($v->runtime);
            if (isset($v->trailer->href))
                $tmp["Bande annonce"]=   $v->trailer->href;
            if (isset($v->statistics->userRating))
                $tmp["Note des spectacteurs"]= $v->statistics->userRating;
            if(isset($v->statistics->pressRating))
                $tmp["Note de la presse"]=  $v->statistics->pressRating;
            if(isset($v->release->releaseDate))
                $tmp["Date de sortie"]= preg_replace("#(\d+)\-(\d+)\-(\d+)#", "$3/$2/$1",  $v->release->releaseDate);
            if (!isset($tmp["Date de sortie"]) ){
                $content = file_get_contents("http://www.allocine.fr/film/fichefilm_gen_cfilm=".$tmp["code"].".html");
                if ( preg_match('#datePublished"[^>]+>(\d+) (janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre) (\d+)#', $content,$o)){
                    //">(\d+) (janvier|février|mars|avril|mai|juin|juillet|août|septembre|octobre|novembre|décembre) (\d+)

                    $ta["janvier"]= "01";
                    $ta["février"]= "02";
                    $ta["mars"]= "03";
                    $ta["avril"]= "04";
                    $ta["mai"]= "05";
                    $ta["juin"]= "06";
                    $ta["juillet"]= "07";
                    $ta["août"]= "08";
                    $ta["septembre"]= "09";
                    $ta["octobre"]= "10";
                    $ta["novembre"]= "11";
                    $ta["décembre"]= "12";
                    $tmp["Date de sortie"]= $o[1]."/".$ta[$o[2]]."/".$o[3];
                }
            }
            if (isset($v->release->distributor->name))
                $tmp["Distributeur"]= $v->release->distributor->name;
            if (isset($v->movieType->_))
                $tmp["Type du film"] =  $v->movieType->_;
            if (isset($v->castingShort->directors))
                $tmp["Réalisateur"] =  $v->castingShort->directors;
            if (isset($v->castingShort->actors))
                $tmp["Acteur(s)"] =  $v->castingShort->actors;
            foreach($v->media AS $k=>$vv){
                if ( $vv->class === "picture"){
                    $width=0;
                    $height=0;
                    if ( isset($vv->width) && isset($vv->height)){
                        $width = $vv->width;
                        $height = $vv->height;
                    }else{
                        $info = getimagesize ( $vv->thumbnail->href );
                        $width = $info[0];
                        $height = $info[1];
                    }
                    if ( $width > $height){
                        //Backdrop
                        $tmp["imagebackdrop"][]= array($vv->thumbnail->href,$width,$height);
                    }else{
                        //Poster
                        $tmp["imageposter"][]= array($vv->thumbnail->href,$width,$height);
                    }
                }
            }
            $tmdb = new TheMovieDb();
            $tmp1 = $tmdb->searchFilm($v->originalTitle,"en");
            if ( isset($tmp1->results)){
            $tmp1 =$tmdb->getMovieImage($tmp1->results[0]->id);
                foreach( $tmp1->backdrops as $k=>$vv){
                    //var_dump($vv);
                    //die();
                    $tmp["imagebackdrop"][]= array("http://image.tmdb.org/t/p/original".$vv->file_path,$vv->width,$vv->height);
                }
                foreach( $tmp1->posters as $k=>$vv){
                    $tmp["imageposter"][]= array("http://image.tmdb.org/t/p/original".$vv->file_path,$vv->width,$vv->height);
                }
            }
                if (isset($v->movieCertificate->certificate->_))
                $tmp["Interdiction"] =  $v->movieCertificate->certificate->_;
            if (isset($v->nationality)){
                $tmp["Origine"] = "";
                foreach ($v->nationality as $k=>$vs)
                    $tmp["Origine"] .= $vs->_.", ";
                $tmp["Origine"] = substr($tmp["Origine"],0,-2);
            }
            if (isset($v->genre)){
                $tmp["Genre"] = "";
                foreach ($v->genre as $k=>$vs)
                    $tmp["Genre"] .= $vs->_.", ";
                $tmp["Genre"] = substr($tmp["Genre"],0,-2);
            }
            if (isset($v->synopsis))
                $tmp["Synopsis"] =  $v->synopsis;
            if  (isset($v->synopsisShort))
                $tmp["synopsiscourt"] = $v->synopsisShort;
            return $tmp;
        }
        return null;

    }
    function affiche($a){
        var_dump($a);

    }
} 