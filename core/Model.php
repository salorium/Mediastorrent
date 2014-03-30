<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 25/10/13
 * Time: 11:38
 * To change this template use File | Settings | File Templates.
 */

namespace core;


class Model {
    function __construct(){
    }
    public function __toString(){
        $res = "";
        foreach (get_object_vars($this) as $k=>$v){
            if (is_bool($v)){
                $res.= "&nbsp;&nbsp;".$k."=>".($v ? "true":"false").", \n";
            }elseif(is_array($v)){
                $res.= "&nbsp;&nbsp;".$k."=>".$this->toStringArray($v,"&nbsp;&nbsp;");
            }
            else{
                $res.= "&nbsp;&nbsp;".$k."=>".$v.", \n";
            }

        }
        return "[".get_class($this)."]\n".$res;
    }

    private function toStringArray($array,$prefix){

        $res ="[\n";
        foreach ($array as $k=>$v){
            $res.= $prefix."&nbsp;&nbsp;".$k."=>".$v.", \n";
        }
        if ( strlen($res)>3){
            $res .=$prefix."]\n";
        }else{
            $res = "[]\n";
        }
        return $res;
    }
}