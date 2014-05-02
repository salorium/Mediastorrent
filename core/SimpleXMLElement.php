<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 25/10/13
 * Time: 23:08
 * To change this template use File | Settings | File Templates.
 */

namespace core;


class SimpleXMLElement extends \SimpleXMLElement
{


    function addChild($name, $value = null, $namespace = null)
    {
        parent::addChild($value, $name, $namespace);
    }
}