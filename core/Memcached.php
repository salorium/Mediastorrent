<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 22/03/14
 * Time: 14:54
 */

namespace core;


class Memcached extends \Memcached
{
    static $request;
    static private $cachelocal = array();
    static private $cache = array();
    static public $time = 0;
    static public $instance = array();

    function __construct($persistent_id = null)
    {
        if (is_null($persistent_id)) {
            parent::__construct();
            $this->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
            if (!count($this->getServerList())) {
                $this->addServers(\config\Conf::$memcachedserver);
            }
        } else {
            parent::__construct($persistent_id);
            $this->setOption(\Memcached::OPT_PREFIX_KEY, $persistent_id);
            $this->setOption(\Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
            if (!count($this->getServerList())) {
                $this->addServers(\config\Conf::$memcachedserver);
            }
        }


    }

    function get1($key)
    {
        $QueryStartTime = microtime(true);
        $q = "";
        if (isset(self::$cache[$this->getOption(\Memcached::OPT_PREFIX_KEY)][$key])) {
            $res = self::$cache[$this->getOption(\Memcached::OPT_PREFIX_KEY)][$key];
            $rc = 0;
            $rm = "SUCCESS";
            $q = "GET_L";
        } else {
            $res = parent::get($key);
            $rc = $this->getResultCode();
            $rm = $this->getResultMessage();
            $q = "GET_S";
            if ($rc !== \Memcached::RES_SUCCESS)
                $res = null;
            self::$cache[$this->getOption(\Memcached::OPT_PREFIX_KEY)][$key] = $res;
        }
        $QueryEndTime = microtime(true);
        self::$time += ($QueryEndTime - $QueryStartTime) * 1000;
        self::$request[] = array($q, ($QueryEndTime - $QueryStartTime) * 1000, $this->getOption(\Memcached::OPT_PREFIX_KEY), $key, $res, $rc, $rm);
        return $res;
    }

    function set($key, $value, $expiration = NULL, $udf_flags = NULL)
    {
        $QueryStartTime = microtime(true);
        $res = parent::set($key, $value, $expiration, $udf_flags);
        $rc = $this->getResultCode();
        $rm = $this->getResultMessage();
        //if ( $res){
        self::$cache[$this->getOption(\Memcached::OPT_PREFIX_KEY)][$key] = $value;
        //}
        $QueryEndTime = microtime(true);
        self::$time += ($QueryEndTime - $QueryStartTime) * 1000;
        self::$request[] = array("SET", ($QueryEndTime - $QueryStartTime) * 1000, $this->getOption(\Memcached::OPT_PREFIX_KEY), $key, $value, $rc, $rm);
        return $res;
    }

    public static function getInstance($database = null)
    {
        if (is_null($database))
            return new Memcached();
        if (!array_key_exists($database, self::$instance)) {
            self::$instance[$database] = new Memcached($database);


        }
        return self::$instance[$database];
    }

    public static function value($database, $clef, $valeur = null, $duree = 2592000)
    {
        $QueryStartTime = microtime(true);
        $m = self::getInstance($database);
        if (is_null($valeur)) {
            //Get value
            /*if (isset(self::$cache[$database][$clef])){
                $res = self::$cache[$database][$clef];
                $rc = 0;
                $rm = "SUCCESS";
                $QueryEndTime = microtime(true);
                self::$time += ($QueryEndTime - $QueryStartTime) * 1000;
                self::$request[] = array("GET_SANS_MEMCACHED", ($QueryEndTime - $QueryStartTime) * 1000,$database , $clef,$res,$rc,$rm);
            }else{*/
            $res = $m->get($clef);
            /*$rc = $m->getResultCode();
            $rm = $m->getResultMessage();
            if ( $m->getResultCode() !== \Memcached::RES_SUCCESS)
                $res = null;*/

            //}
        } else {
            //set Value
            $res = $m->set($clef, $valeur, $duree);
            /*if ($res)
                self::$cache[$database][$clef] = $valeur;*/
        }

        if (is_null($valeur)) {
            //    self::$request[] = array("GET", ($QueryEndTime - $QueryStartTime) * 1000,$database , $clef,$res,$rc,$rm);
        } else {
            //  self::$request[] = array("SET", ($QueryEndTime - $QueryStartTime) * 1000,$database ,$clef,$valeur,$m->getResultCode(),$m->getResultMessage());
        }
        return $res;
    }

} 