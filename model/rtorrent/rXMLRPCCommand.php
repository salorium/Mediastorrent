<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 24/03/14
 * Time: 23:55
 */

namespace model\rtorrent;


class rXMLRPCCommand extends \core\Model
{
    public $command;
    public $params;
    public $user;

    public function __construct($cmd, $args = null)
    {
        //\trigger_error("ddd");
        //throw new \Exception("ddd");
        $this->command = $cmd;
        $this->params = array();
        if ($args !== null) {
            if (is_array($args))
                foreach ($args as $prm)
                    $this->addParameter($prm);
            else
                $this->addParameter($args);
        }
    }

    public function addParameters($args)
    {
        if ($args !== null) {
            if (is_array($args))
                foreach ($args as $prm)
                    $this->addParameter($prm);
            else
                $this->addParameter($args);
        }
    }

    public function addParameter($aValue, $aType = null)
    {
        if ($aType === null)
            $aType = self::getPrmType($aValue);
        $this->params[] = new rXMLRPCParam($aType, $aValue);
    }

    static protected function getPrmType($prm)
    {
        if (is_int($prm))
            return ('i4');
        if (is_double($prm))
            return ('i8');
        return ('string');
    }
} 