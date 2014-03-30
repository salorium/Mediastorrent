<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 24/03/14
 * Time: 23:57
 */

namespace model\xmlrpc;


class rXMLRPCParam extends \core\Model {
    public $type;
    public $value;

    public function __construct( $aType, $aValue )
    {
        $this->type = $aType;
        if(($this->type=="i8") || ($this->type=="i4"))
            $this->value = \number_format($aValue,0,'.','');
        else
            $this->value = \htmlspecialchars($aValue,ENT_NOQUOTES,"UTF-8");
    }
} 