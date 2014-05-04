<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 13/04/14
 * Time: 20:10
 */

namespace model\simple;


use core\Model;

class TheMovieDb extends Model
{
    private $api_key;
    private $url = "http://api.themoviedb.org/3/";
    private $param;

    function __construct()
    {
        $this->api_key = \config\Conf::$api_key_themoviedb;
    }

    function getCloud()
    {
        $this->param = array(
            'api_key' => $this->api_key
        );
        return $this->lookUrl($this->url . "configuration?");
    }

    function lookUrl($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url . http_build_query($this->param));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Accept: application/json"));
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);
    }

    function searchFilm($search, $lang = "fr")
    {
        $this->param = array(
            'query' => $search,
            'language' => $lang,
            'search_type' => 'ngram',
            'api_key' => $this->api_key
        );
        return $this->lookUrl($this->url . "search/movie?");
    }

    function searchSerie($search, $lang = "fr")
    {
        $this->param = array(
            'query' => $search,
            'language' => $lang,
            'search_type' => 'ngram',
            'api_key' => $this->api_key
        );
        return $this->lookUrl($this->url . "search/tv?");
    }

    function getMovie($id, $lang = "fr")
    {
        $this->param = array(
            'append_to_response' => "casts",
            'language' => $lang,
            'api_key' => $this->api_key
        );
        return $this->lookUrl($this->url . "movie/" . $id . "?");
    }

    function getSerie($id, $lang = "fr")
    {
        $this->param = array(
            'append_to_response' => "casts",
            'language' => $lang,
            'api_key' => $this->api_key
        );
        return $this->lookUrl($this->url . "tv/" . $id . "?");
    }

    /*function getMovieJson($id,$lang="fr"){
        $this->param =  array(
            'append_to_response' => "casts",
            'language' => $lang,
            'api_key'=> $this->api_key
        );
       // header('Content-Type: application/json');
       // return json_encode($this->lookUrl($this->url."movie/".$id."?"));
    }*/
    private function dateFormat($date)
    {
        $heure = floor($date / 60);
        $date = $date % 60;
        $minute = $date;
        return substr(($heure != 0 ? $heure . " h " : "") . ($minute != 0 ? $minute . " min " : $minute . " min"), 0, -1);
    }

    function  getMovieImage($id, $lang = "fr")
    {
        $this->param = array(
            'api_key' => $this->api_key
        );
        return $this->lookUrl($this->url . "movie/" . $id . "/images?");
    }

    function  getSerieImage($id, $lang = "fr")
    {
        $this->param = array(
            'api_key' => $this->api_key
        );
        return $this->lookUrl($this->url . "tv/" . $id . "/images?");
    }

    function getMovieFormat($id, $lang = "fr")
    {
        $pays = Iso31::getIso3166($lang);
        $t = null;
        $setting = $this->getCloud();
        $response = $this->getMovie($id, $lang);
        $t["code"] = $id;
        if (isset($response->original_title))
            $t["Titre Original"] = $response->original_title;
        if (isset($response->title))
            $t["Titre"] = $response->title;
        if (isset($response->runtime))
            $t["Durée"] = $this->dateFormat($response->runtime);
        if (isset($response->release_date))
            $t["Date de sortie"] = preg_replace("#(\d+)\-(\d+)\-(\d+)#", "$3/$2/$1", $response->release_date);
        if (isset($response->production_companies)) {
            $res = "";
            foreach ($response->production_companies as $k => $v)
                $res .= ", " . $v->name;
            $t["Distributeur"] = substr($res, 2);

        }
        if (isset($response->casts->crew)) {

            $res = "";
            for ($i = 0; $i < count($response->casts->crew); $i++) {
                if ($response->casts->crew[$i]->job == "Director")
                    $res .= ", " . $response->casts->crew[$i]->name;
            }
            $t["Réalisateur"] = substr($res, 2);
        }
        if (isset($response->casts->cast)) {
            $max = 5;
            if (count($response->casts->cast) < 5) {
                $max = count($response->casts->cast);
            }
            $res = "";
            for ($i = 0; $i < $max; $i++) {
                $res .= ", " . $response->casts->cast[$i]->name;
            }
            $t["Acteur(s)"] = substr($res, 2);
        }
        if (isset($response->poster_path))
            $t["poster"] = $setting->images->secure_base_url . 'original' . $response->poster_path;

        if (isset($response->production_countries)) {
            $res = "";
            foreach ($response->production_countries as $k => $v)
                $res .= ", " . $pays[$v->iso_3166_1];
            $t["Origine"] = substr($res, 2);

        }
        if (isset($response->genres)) {
            $res = null;
            foreach ($response->genres as $k => $v)
                $res[] = $v->name;
            $t["Genre"] = $res;
        }
        if (isset($response->overview))
            $t["Synopsis"] = $response->overview;
        if (isset($response->backdrop_path))
            $t["fond"] = $setting->images->secure_base_url . "original" . $response->backdrop_path;
        return $t;
    }

} 