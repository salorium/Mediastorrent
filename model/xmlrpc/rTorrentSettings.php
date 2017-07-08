<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 24/03/14
 * Time: 23:59
 */

namespace model\xmlrpc;


use core\Memcached;

class rTorrentSettings extends \core\Model
{
    public $linkExist = false;
    public $badXMLRPCVersion = true;
    public $directory = '/tmp';
    public $session = null;
    public $gid = array();
    public $uid = -1;
    public $iVersion = null;
    public $version;
    public $libVersion;
    public $apiVersion = 0;
    public $plugins = array();
    public $hooks = array();
    public $mostOfMethodsRenamed = false;
    public $aliases = array();
    public $started = 0;
    public $server = '';
    public $portRange = '6890-6999';
    public $port = '6890';
    public $idNotFound = false;
    public $home = '';
    public $portscgi;
    public $user;
    public $pid = null;
    static private $theSettings = null;

    function __construct($user)
    {
        $this->user = $user;
    }

    private function __clone()
    {
    }

    public function store()
    {
        return (Memcached::value("rtorrent" . $this->user, "rtorrentsetting", $this));
    }

    static public function get($user, $create = false)
    {
        if (is_null(self::$theSettings)) {
            self::$theSettings = new rTorrentSettings($user);
            if ($create)
                self::$theSettings->obtain();
            else {
                $res = Memcached::value("rtorrent" . $user, "rtorrentsetting");
                if (!$res) {
                    self::$theSettings->obtain();

                } else {
                    self::$theSettings = $res;
                }

            }
        }
        return (self::$theSettings);
    }

    public function obtain()
    {
        $req = new rXMLRPCRequest($this->user, new rXMLRPCCommand($this->user, "system.client_version"));
        if ($req->run() && count($req->val)) {
            $this->linkExist = true;
            $this->version = $req->val[0];
            $parts = explode('.', $this->version);
            $this->iVersion = 0;
            for ($i = 0; $i < count($parts); $i++)
                $this->iVersion = ($this->iVersion << 8) + $parts[$i];

            if ($this->iVersion > 0x806) {
                $this->mostOfMethodsRenamed = true;
                $this->aliases = array(
                    "d.set_peer_exchange" => "d.peer_exchange.set",
                    "d.set_connection_seed" => "d.connection_seed.set",
                );
            }
            if ($this->iVersion == 0x808) {
                $req = new rXMLRPCRequest($this->user, new rXMLRPCCommand($this->user, "file.prioritize_toc"));
                $req->important = false;
                if ($req->success())
                    $this->iVersion = 0x809;
            }
            $this->apiVersion = 0;
            if ($this->iVersion >= 0x901) {
                $req = new rXMLRPCRequest($this->user, new rXMLRPCCommand($this->user, "system.api_version"));
                $req->important = false;
                if ($req->success())
                    $this->apiVersion = $req->val[0];
            }

            $req = new rXMLRPCRequest($this->user, new rXMLRPCCommand($this->user, "to_kb", floatval(1024)));
            if ($req->run()) {
                if (!$req->fault)
                    $this->badXMLRPCVersion = false;
                $req = new rXMLRPCRequest($this->user, array(
                    new rXMLRPCCommand($this->user, "directory.default"),
                    new rXMLRPCCommand($this->user, "session.path"),
                    new rXMLRPCCommand($this->user, "system.library_version"),
                    new rXMLRPCCommand($this->user, "network.xmlrpc.size_limit.set", array("", "10M")),
                    new rXMLRPCCommand($this->user, "session.name"),
                    new rXMLRPCCommand($this->user, "network.port_range"),
                    new rXMLRPCCommand($this->user, "system.pid"),
                ));
                if ($req->run() && !$req->fault) {
                    $this->directory = $req->val[0];
                    $this->session = $req->val[1];
                    $this->libVersion = $req->val[2];
                    $this->server = $req->val[4];
                    $this->portRange = $req->val[5];
                    $this->pid = intval($req->val[6]);
                    $this->port = intval($this->portRange);

                    if ($this->iVersion >= 0x809) {
                        $req = new rXMLRPCRequest($this->user, new rXMLRPCCommand($this->user, "network.listen.port"));
                        $req->important = false;
                        if ($req->success())
                            $this->port = intval($req->val[0]);
                    }

                    /*if(isLocalMode())
                    {*/
                    if(!empty($this->session))
                        {
                            $this->started = @filemtime($this->session.'/rtorrent.lock');
                            if($this->started===false)
                                $this->started = 0;
                        }
                    $id = "id"; //getExternal('id');
                    $req = new rXMLRPCRequest($this->user,
                        new rXMLRPCCommand($this->user, "execute.capture", array("", "sh", "-c", $id . " -u ; " . $id . " -G ; echo ~ ")));
                    if ($req->run() && !$req->fault && (($line = explode("\n", $req->val[0])) !== false) && (count($line) > 2)) {
                        $this->uid = intval(trim($line[0]));
                            $this->gid = explode(' ',trim($line[1]));
                            $this->home = trim($line[2]);
                            if(!empty($this->directory) &&
                                ($this->directory[0]=='~'))
                                $this->directory = $this->home.substr($this->directory,1);
                        }
                        else
                            $this->idNotFound = true;
                    /*}*/
                    $this->store();
                }
            }
        }
    }

    public function getCommand($cmd)
    {
        $add = '';
        $len = strlen($cmd);
        if ($len && ($cmd[$len - 1] == '=')) {
            $cmd = substr($cmd, 0, -1);
            $add = '=';
        }
        return (array_key_exists($cmd, $this->aliases) ? $this->aliases[$cmd] . $add : $cmd . $add);
    }

    public function getEventCommand($cmd1, $cmd2, $args)
    {
        if ($this->iVersion < 0x804)
            $cmd = new rXMLRPCCommand($this->user, $cmd1);
        else
//		if($this->mostOfMethodsRenamed)
//			$cmd = new rXMLRPCCommand('method.set_key','event.download.'.$cmd2);
//		else
            $cmd = new rXMLRPCCommand($this->user, 'system.method.set_key', 'event.download.' . $cmd2);
        $cmd->addParameters($args);
        return ($cmd);
    }

    public function getOnInsertCommand($args)
    {
        return ($this->getEventCommand('on_insert', 'inserted_new', $args));
    }

    public function getOnEraseCommand($args)
    {
        return ($this->getEventCommand('on_erase', 'erased', $args));
    }

    public function getOnFinishedCommand($args)
    {
        return ($this->getEventCommand('on_finished', 'finished', $args));
    }

    public function getOnResumedCommand($args)
    {
        return ($this->getEventCommand('on_start', 'resumed', $args));
    }

    public function getOnHashdoneCommand($args)
    {
        return ($this->getEventCommand('on_hash_done', 'hash_done', $args));
    }

    public static function getCmd($user, $cmd)
    {
        return rTorrentSettings::get($user, true)->getCommand($cmd);
    }
    /*
    public function getAbsScheduleCommand($name,$interval,$cmd)	// $interval in seconds
    {
        global $schedule_rand;
        if(!isset($schedule_rand))
            $schedule_rand = 10;
        $startAt = $interval+rand(0,$schedule_rand);
        return( new rXMLRPCCommand("schedule", array( $name.getUser(), $startAt."", $interval."", $cmd )) );
    }
    public function getScheduleCommand($name,$interval,$cmd,&$startAt = null)	// $interval in minutes
    {
        global $schedule_rand;
        if(!isset($schedule_rand))
            $schedule_rand = 10;
        $tm = getdate();
        $startAt = mktime($tm["hours"],
                ((integer)($tm["minutes"]/$interval))*$interval+$interval,
                0,$tm["mon"],$tm["mday"],$tm["year"])-$tm[0]+rand(0,$schedule_rand);
        if($startAt<0)
            $startAt = 0;
        $interval = $interval*60;
        return( new rXMLRPCCommand("schedule", array( $name.getUser(), $startAt."", $interval."", $cmd )) );
    }
    public function getRemoveScheduleCommand($name)
    {
        return(	new rXMLRPCCommand("schedule_remove", $name.getUser()) );
    }*/

    public function correctDirectory(&$dir,$resolve_links = false)
    {
        global $topDirectory;
        if(strlen($dir) && ($dir[0]=='~'))
            $dir = $this->home.substr($dir,1);
        $dir = fullpath($dir,$this->directory);
        if($resolve_links)
        {
            $path = realpath($dir);
            if(!$path)
                $dir = addslash(realpath(dirname($dir))).basename($dir);
            else
                $dir = $path;
        }
        return(strpos(addslash($dir),$topDirectory)===0);
    }

} 