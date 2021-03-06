<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 24/03/14
 * Time: 23:39
 */

namespace model\xmlrpc;


use core\Debug;

class rXMLRPCRequest extends \core\Model
{
    public static $rpcTimout = 5;
    protected $commands = array();
    protected $content = "";
    public $i8s = array();
    public $strings = array();
    public $val = array();
    public $vals = array();
    public $fault = false;
    public $parseByTypes = false;
    public $important = true;
    public $portscgi;
    public $userscgi;
    public static $query = null;
    public static $time = 0;

    /**
     * @param null $cmds
     */
    public function __construct($userscgi, $cmds = null)
    {
        $this->userscgi = $userscgi;
        if ($cmds) {
            if (is_array($cmds))
                foreach ($cmds as $cmd)
                    $this->addCommand($cmd);
            else
                $this->addCommand($cmds);
        }
    }

    public static function send($data, $user)
    {
        /*if(Variable::$rpc_call)
            toLog($data);*/
        $QueryStartTime = \microtime(true);
        $scgi_host = "unix:///home/$user/rtorrent/session/rpc.socket";
        //$scgi_port = $portscgi;
        $result = false;
        $contentlength = strlen($data);
        $d = $data;
        if ($contentlength > 0) {
            $socket = fsockopen($scgi_host, -1, $errno, $errstr, rXMLRPCRequest::$rpcTimout);
            if ($socket) {
                $reqheader = "CONTENT_LENGTH\x0" . $contentlength . "\x0" . "SCGI\x0" . "1\x0";
                $tosend = strlen($reqheader) . ":{$reqheader},{$data}";
                @fwrite($socket, $tosend, strlen($tosend));
                $result = '';
                while ($data = fread($socket, 4096))
                    $result .= $data;
                fclose($socket);
            }
        }
        $QueryEndTime = microtime(true);
        self::$time += ($QueryEndTime - $QueryStartTime) * 1000;
        self::$query[] = array($d, ($QueryEndTime - $QueryStartTime) * 1000, $result);
        /*if(Variable::$rpc_call)
            toLog($result);*/
        return ($result);
    }

    public function setParseByTypes($enable = true)
    {
        $this->parseByTypes = $enable;
    }

    public function getCommandsCount()
    {
        return (count($this->commands));
    }

    protected function makeCall()
    {
        $this->fault = false;
        $this->content = "";
        $cnt = count($this->commands);
        if ($cnt > 0) {
            $this->content = '<?xml version="1.0" encoding="UTF-8"?><methodCall><methodName>';
            if ($cnt == 1) {
                $cmd = $this->commands[0];
                $this->content .= "{$cmd->command}</methodName><params>\r\n";
                foreach ($cmd->params as &$prm)
                    $this->content .= "<param><value><{$prm->type}>{$prm->value}</{$prm->type}></value></param>\r\n";
            } else {
                $this->content .= "system.multicall</methodName><params><param><value><array><data>";
                foreach ($this->commands as &$cmd) {
                    $this->content .= "\r\n<value><struct><member><name>methodName</name><value><string>" .
                        "{$cmd->command}</string></value></member><member><name>params</name><value><array><data>";
                    foreach ($cmd->params as &$prm)
                        $this->content .= "\r\n<value><{$prm->type}>{$prm->value}</{$prm->type}></value>";
                    $this->content .= "\r\n</data></array></value></member></struct></value>";
                }
                $this->content .= "\r\n</data></array></value></param>";
            }
            $this->content .= "</params></methodCall>";
        }
        return ($cnt > 0);
    }

    public function addCommand($cmd)
    {
        $this->commands[] = $cmd;
    }

    public function run($factory = true)
    {
        $ret = false;
        $this->i8s = array();
        $this->strings = array();
        $this->val = array();
        Debug::startTimer("makecall");
        if ($this->makeCall()) {
            Debug::endTimer("makecall");
            Debug::startTimer("send");
            $answer = self::send($this->content, $this->userscgi);
            Debug::endTimer("send");

            if (!empty($answer)) {
                if ($factory) {
                    Debug::startTimer("refac");
                    if ($this->parseByTypes) {
                        if ((preg_match_all("|<value><string>(.*)</string></value>|Us", $answer, $this->strings) !== false) &&
                            count($this->strings) > 1 &&
                            (preg_match_all("|<value><i.>(.*)</i.></value>|Us", $answer, $this->i8s) !== false) &&
                            count($this->i8s) > 1
                        ) {
                            $this->strings = str_replace("\\", "\\\\", $this->strings[1]);
                            $this->strings = str_replace("\"", "\\\"", $this->strings);
                            foreach ($this->strings as &$string)
                                $string = html_entity_decode($string, ENT_COMPAT, "UTF-8");
                            $this->i8s = $this->i8s[1];
                            $ret = true;
                        }
                    } else {
                        Debug::startTimer("refacpreg");
                        if ((preg_match_all("/<value>(<string>|<i.>)(.*)((\n)?<\/string>|<\/i.>)<\/value>/Us", $answer, $this->val) !== false) &&
                            count($this->val) > 2
                        ) {
                            Debug::startTimer("refacfor");
                            $this->val = $this->val[2];
                            Debug::endTimer("refacfor");
                            $ret = true;
                        }
                        Debug::endTimer("refacpreg");
                    }
                    Debug::endTimer("refac");
                } else {
                    $ret = true;
                    $this->val = $answer;
                }
                if ($ret) {
                    if (strstr($answer, "faultCode") !== false) {
                        //trigger_error($answer);
                        $this->fault = true;
                        if ( /*LOG_RPC_FAULTS*/
                            false && $this->important
                        ) {
                            /*toLog($this->content);
                            toLog($answer);*/
                        }
                    }
                }

            }
            //trigger_error($answer);
        }
        $this->content = "";
        $this->commands = array();
        return ($ret);
    }

    public function success($factory = true)
    {
        Debug::startTimer("sucess");
        $res = ($this->run($factory) && !$this->fault);
        Debug::endTimer("sucess");

        return $res;

    }

    public function getValueParsed()
    {
        $res = array();

    }

} 