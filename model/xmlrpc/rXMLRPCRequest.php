<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 24/03/14
 * Time: 23:39
 */

namespace model\xmlrpc;


class rXMLRPCRequest extends \core\Model{
    public static $rpcTimout = 5;
    protected $commands = array();
    protected $content = "";
    public $i8s = array();
    public $strings = array();
    public $val = array();
    public $fault = false;
    public $parseByTypes = false;
    public $important = true;
    public $portscgi;

    /**
     * @param null $cmds
     */
    public function __construct($portscgi, $cmds = null )
    {
        $this->portscgi = $portscgi;
        if($cmds)
        {
            if(is_array($cmds))
                foreach($cmds as $cmd)
                    $this->addCommand($cmd);
            else
                $this->addCommand($cmds);
        }
    }

    public static function send( $data,$portscgi )
    {
        /*if(Variable::$rpc_call)
            toLog($data);*/
        $scgi_host = "127.0.0.1";
        $scgi_port = $portscgi;
        $result = false;
        $contentlength = strlen($data);
        if($contentlength>0)
        {
            $socket = @fsockopen($scgi_host, $scgi_port, $errno, $errstr, rXMLRPCRequest::$rpcTimout);
            if($socket)
            {
                $reqheader =  "CONTENT_LENGTH\x0".$contentlength."\x0"."SCGI\x0"."1\x0";
                $tosend = strlen($reqheader).":{$reqheader},{$data}";
                @fwrite($socket,$tosend,strlen($tosend));
                $result = '';
                while($data = fread($socket, 4096))
                    $result .= $data;
                fclose($socket);
            }
        }
        /*if(Variable::$rpc_call)
            toLog($result);*/
        return($result);
    }

    public function setParseByTypes( $enable = true )
    {
        $this->parseByTypes = $enable;
    }

    public function getCommandsCount()
    {
        return(count($this->commands));
    }

    protected function makeCall()
    {
        $this->fault = false;
        $this->content = "";
        $cnt = count($this->commands);
        if($cnt>0)
        {
            $this->content = '<?xml version="1.0" encoding="UTF-8"?><methodCall><methodName>';
            if($cnt==1)
            {
                $cmd = $this->commands[0];
                $this->content .= "{$cmd->command}</methodName><params>\r\n";
                foreach($cmd->params as &$prm)
                    $this->content .= "<param><value><{$prm->type}>{$prm->value}</{$prm->type}></value></param>\r\n";
            }
            else
            {
                $this->content .= "system.multicall</methodName><params><param><value><array><data>";
                foreach($this->commands as &$cmd)
                {
                    $this->content .= "\r\n<value><struct><member><name>methodName</name><value><string>".
                        "{$cmd->command}</string></value></member><member><name>params</name><value><array><data>";
                    foreach($cmd->params as &$prm)
                        $this->content .= "\r\n<value><{$prm->type}>{$prm->value}</{$prm->type}></value>";
                    $this->content .= "\r\n</data></array></value></member></struct></value>";
                }
                $this->content .= "\r\n</data></array></value></param>";
            }
            $this->content .= "</params></methodCall>";
        }
        return($cnt>0);
    }

    public function addCommand( $cmd )
    {
        $this->commands[] = $cmd;
    }

    public function run()
    {
        $ret = false;
        $this->i8s = array();
        $this->strings = array();
        $this->val = array();
        if($this->makeCall())
        {
            $answer = self::send($this->content,$this->portscgi);
            if(!empty($answer))
            {
                if($this->parseByTypes)
                {
                    if((preg_match_all("|<value><string>(.*)</string></value>|Us",$answer,$this->strings)!==false) &&
                        count($this->strings)>1 &&
                        (preg_match_all("|<value><i.>(.*)</i.></value>|Us",$answer,$this->i8s)!==false) &&
                        count($this->i8s)>1)
                    {
                        $this->strings = str_replace("\\","\\\\",$this->strings[1]);
                        $this->strings = str_replace("\"","\\\"",$this->strings);
                        foreach($this->strings as &$string)
                            $string = html_entity_decode($string,ENT_COMPAT,"UTF-8");
                        $this->i8s = $this->i8s[1];
                        $ret = true;
                    }
                }
                else
                {
                    if((preg_match_all("/<value>(<string>|<i.>)(.*)(<\/string>|<\/i.>)<\/value>/Us",$answer,$this->val)!==false) &&
                        count($this->val)>2)
                    {
                        $this->val = str_replace("\\","\\\\",$this->val[2]);
                        $this->val = str_replace("\"","\\\"",$this->val);
                        foreach($this->val as &$string)
                            $string = html_entity_decode($string,ENT_COMPAT,"UTF-8");
                        $ret = true;
                    }
                }
                if($ret)
                {
                    if(strstr($answer,"faultCode")!==false)
                    {
                        //trigger_error($answer);
                        $this->fault = true;
                        if(/*LOG_RPC_FAULTS*/ false && $this->important)
                        {
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
        return($ret);
    }

    public function success()
    {
        return($this->run() && !$this->fault);
    }

    public function getValueParsed(){
        $res =array();

    }

} 