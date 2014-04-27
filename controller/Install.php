<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 26/04/14
 * Time: 13:47
 */

namespace controller;


class Install extends  \core\Controller{
    function index(){
        $this->layout = "install";
        $memachedload = extension_loaded("memcached");
        $this->set(array(
            "memcached"=>$memachedload,
            "mysqli"=>extension_loaded("mysqli"),
            "imagick"=>extension_loaded("imagick"),
            "ecrituredossiercache"=>is_writable(ROOT.DS."cache"),
            "ecrituredossiercache"=>is_writable(ROOT.DS."cache")
        ));
    }
    function enableModule($pass=null,$action=null){
        set_time_limit (60);
        if ( ! is_null($pass) && ! is_null($action)){
            $_REQUEST["password"] = $pass;
            $_REQUEST["action"]= $action;
        }
        if (isset( $_REQUEST["password"])&& isset( $_REQUEST["action"])){$tmp=null;
            switch ( $_REQUEST["action"]){
                case "mysqli":
                    $ti = \model\simple\Ssh::execute("root",$_REQUEST["password"],"apt-get -y install php5-mysqlnd");
                    $t[]= $ti;
                    if ( $ti["error"] !== ""){
                        $ti = \model\simple\Ssh::execute("root",$_REQUEST["password"],"apt-get -y install php5-mysql");
                        $t[]= $ti;
                        $ti = \model\simple\Ssh::execute("root",$_REQUEST["password"],"dpkg --configure -a");
                        $t[]= $ti;

                    }
                    $t[] = \model\simple\Ssh::execute("root",$_REQUEST["password"],"service apache2 reload");
                    break;
                default:
                    $t[] = \model\simple\Ssh::execute("root",$_REQUEST["password"],"apt-get -y install php5-".$_REQUEST["action"]);
                    $ti = \model\simple\Ssh::execute("root",$_REQUEST["password"],"dpkg --configure -a");
                    $t[]= $ti;
                    $t[] = \model\simple\Ssh::execute("root",$_REQUEST["password"],"service apache2 reload");
                    break;
            }
            $this->set(array(
                "result"=> $t,
                "tmp"=>$tmp
            ));
        }

    }
    function enableWriteFile($pass=null,$file=null){
        if ( ! is_null($pass) && ! is_null($file)){
            $_REQUEST["password"] = $pass;
            $_REQUEST["file"]= $file;
        }
        if (isset( $_REQUEST["password"])&& isset( $_REQUEST["file"])){
            $t = null;
            $write = false;
            switch ( $_REQUEST["file"]){
                case "cache":
                    $ti = \model\simple\Ssh::execute("root",$_REQUEST["password"],"chmod -R a+w ".ROOT.DS."cache");
                    $t[]= $ti;
                    $write = is_writable(ROOT.DS."cache");
                    break;

            }

            $this->set(array(
                "result"=> $t,
                "ecriture"=>$write
            ));
        }
    }
    function checkModule($module){
        $this->set(array(
            "extension"=> extension_loaded($module)
        ));
    }
    function restartApache($pass=null){
        if ( !is_null($pass)){
            $t[] = \model\simple\Ssh::execute("root",$pass,"service apache2 reload");
            $this->set(array(
                "result"=> $t
            ));
        }
    }
} 