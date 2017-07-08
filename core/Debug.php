<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 20/09/13
 * Time: 21:01
 * To change this template use File | Settings | File Templates.
 */

namespace core;


class Debug
{
    private $timestart;
    private $cpustart;
    private $dispatcher;
    static $error = null;
    static $fatal = null;
    static $that = null;
    static $timelog = null;
    static $cpt = 0;
    private $loggedVars;

    function __construct($dispatcher)
    {
        self::$that = $this;
        $this->timestart = \microtime(true);
        $this->dispatcher = $dispatcher;
        if (!defined('PHP_WINDOWS_VERSION_MAJOR')) {
            $RUsage = \getrusage();
            $this->cpustart = $RUsage['ru_utime.tv_sec'] * 1000000 + $RUsage['ru_utime.tv_usec'];
        }
    }

    static function plus()
    {
        self::$cpt++;
    }
    static function startTimer($nom)
    {
        self::$timelog[$nom] = \microtime(true);
    }

    static function endTimer($nom)
    {
        if (isset(self::$timelog[$nom])) {
            $d = self::$timelog[$nom];
            self::$timelog[$nom] = (\microtime(true) - $d) * 1000;
        }
    }

    function handle_errors()
    {
//error_reporting(E_ALL ^ E_STRICT | E_WARNING | E_DEPRECATED | E_ERROR | E_PARSE); //E_STRICT disabled
        \error_reporting(E_ALL);
        \set_error_handler(array('\core\Debug', 'php_error_handler'));
        \register_shutdown_function(array('\core\Debug', 'shutDownFunction'));
    }

    public static function php_error_handler($Level, $Error, $File, $Line)
    {
        if (0 === error_reporting()) {
            return true;
        }
        //Who added this, it's still something to pay attention to...
        if (stripos('Undefined index', $Error) !== false) {
//return true;
        }

        $Steps = 1; //Steps to go up in backtrace, default one
        $Call = '';
        $Args = '';
        $Tracer = debug_backtrace();

//This is in case something in this function goes wrong and we get stuck with an infinite loop
        if (isset($Tracer[$Steps]['function'], $Tracer[$Steps]['class']) && $Tracer[$Steps]['function'] == 'php_error_handler' && $Tracer[$Steps]['class'] == 'DEBUG') {
            return true;
        }

//If this error was thrown, we return the function which threw it
        if (isset($Tracer[$Steps]['function']) && $Tracer[$Steps]['function'] == 'trigger_error') {
            $File = $Tracer[$Steps]['file'];
            $Line = $Tracer[$Steps]['line'];
            $Steps++;
        }

//At this time ONLY Array strict typing is fully supported.
//Allow us to abuse strict typing (IE: function test(Array))
        if (preg_match('/^Argument (\d+) passed to \S+ must be an (array), (array|string|integer|double|object) given, called in (\S+) on line (\d+) and defined$/', $Error, $Matches)) {
            $Error = 'Type hinting failed on arg ' . $Matches[1] . ', expected ' . $Matches[2] . ' but found ' . $Matches[3];
            $File = $Matches[4];
            $Line = $Matches[5];
        }

//Lets not be repetative
        if (isset($Tracer[$Steps]['function']) && ($Tracer[$Steps]['function'] == 'include' || $Tracer[$Steps]['function'] == 'require') && isset($Tracer[$Steps]['args'][0]) && $Tracer[$Steps]['args'][0] == $File) {
            unset($Tracer[$Steps]['args']);
        }

//Class
        if (isset($Tracer[$Steps]['class'])) {
            $Call .= $Tracer[$Steps]['class'] . '::';
        }

//Function & args
        if (isset($Tracer[$Steps]['function'])) {
            $Call .= $Tracer[$Steps]['function'];
            if (isset($Tracer[$Steps]['args'][0])) {
                $Args = self::format_args($Tracer[$Steps]['args']);
            }
        }

//Shorten the path & we're done
        $File = str_replace(ROOT . DS, '', $File);
        $Error = str_replace(ROOT . DS, '', $Error);

        self::$error[] = array($Error, $File, $Line, $Call, $Args);
        return true;
    }

    protected static function format_args($Array)
    {
        $LastKey = -1;
        $Return = array();
        foreach ($Array as $Key => $Val) {
            $Return[$Key] = '';
            if (!is_int($Key) || $Key != $LastKey + 1) {
                $Return[$Key] .= "'$Key' => ";
            }
            if ($Val === true) {
                $Return[$Key] .= 'true';
            } elseif ($Val === false) {
                $Return[$Key] .= 'false';
            } elseif (is_string($Val)) {
                $Return[$Key] .= "'$Val'";
            } elseif (is_int($Val)) {
                $Return[$Key] .= $Val;
            } elseif (is_object($Val)) {
                $Return[$Key] .= get_class($Val);
            } elseif (is_array($Val)) {
                $Return[$Key] .= 'array(' . self::format_args($Val) . ')';
            }
            $LastKey = $Key;
        }
        return implode(', ', $Return);
    }

    public static function shutDownFunction()
    {
        $error = \error_get_last();
        if (0 === error_reporting()) {
            return true;
        }
        if ($error['type']) {
            //die();
            $error["message"] = str_replace(str_replace("/", DS, ROOT . DS), '', $error["message"]);
            $error["file"] = str_replace(str_replace("/", DS, ROOT . DS), '', $error["file"]);
            self::$fatal[] = $error;
            //Debug::$fatal[]= $error;
            ob_end_clean();
            $r = new Request();
            Router::parse($r->url, $r);
            header("HTTP/1.0 500 Internal Server Error");
            $controller = new Controller($r, self::$that);
            $controller->set($error);
            $controller->render("/errors/fatal");
            die();
        }
    }

    public static function getError()
    {
        return self::$error;
    }

    public static function  getLoggedVar()
    {
        return self::$loggedVars;
    }

    public static function log_var($Var, $VarName = false)
    {
        $BackTrace = debug_backtrace();
        $ID = sha1(uniqid());
        if (!$VarName) {
            $VarName = $ID;
        }
        $File = array('path' => $BackTrace[0]['file'], 'line' => $BackTrace[0]['line']);
        self::$loggedVars[$ID] = array($VarName => array('bt' => $File, 'data' => $Var));
    }

    public function get_cpu_time()
    {
        if (!defined('PHP_WINDOWS_VERSION_MAJOR')) {
            $RUsage = getrusage();

            $CPUTime = $RUsage['ru_utime.tv_sec'] * 1000000 + $RUsage['ru_utime.tv_usec'] - $this->cpustart;
            return $CPUTime;
        }
        return false;
    }


    public function get_perf()
    {
        $PageTime = (microtime(true) - $this->timestart);
        $CPUTime = self::get_cpu_time();
        $Perf = array(
            'RameUsage' => Format::get_size(memory_get_usage(true)),
            'TimePage' => number_format($PageTime, 3) . ' s');
        if ($CPUTime) {
            $Perf['TimeCPU'] = number_format($CPUTime / 1000000, 3) . ' s';
        }
        return $Perf;
    }

    public static function getFatal()
    {
        return self::$fatal;
    }

    public function showIcon()
    {
        if (!is_null(self::$fatal)) {
            return "fatal";
        } else if (!is_null(self::$error)) {
            return "erreur";
        } else {
            return "ok";
        }


    }

    public function showPerformance()
    {
        $t = $this->get_perf();
        extract($t);
        require ROOT . DS . "view" . DS . "html" . DS . "debug" . DS . "performance.php";

    }
}