<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Salorium
 * Date: 23/09/13
 * Time: 12:51
 * To change this template use File | Settings | File Templates.
 */

namespace core;
class Mysqli
{
    private static $dblink;
    public static $query = null;
    public static $time = 0;
    public static $res = null;
    public static $default = "default";
    public static $id = 0;

    private static function connect($host = null, $login = null, $pass = null)
    {
        $conf = \config\Conf::$databases[Mysqli::$default];
        if (!(is_null($host) && is_null($login) && is_null($pass))) {
            $conf["host"] = $host;
            $conf["login"] = $login;
            $conf["password"] = $pass;
            $conf["database"] = "test";
        }
        self::$dblink = new \mysqli($conf["host"], $conf["login"], $conf["password"], $conf["database"]);
        //self::$dblink->connect(Variable::$host_mysql,Variable::$login_mysql,Variable::$password_mysql,Variable::$dbname_mysql);
        if (self::$dblink->connect_errno) {
            throw new \Exception(self::$dblink->connect_error);
        }
    }

    public static function real_escape_string($str)
    {
        if (!isset(self::$dblink)) {
            self::connect();
        }
        if (is_null($str))
            return "NULL";
        return "'" . self::$dblink->real_escape_string(\htmlentities($str, ENT_NOQUOTES)) . "'";
    }

    public static function real_escape_stringlike($str)
    {
        if (!isset(self::$dblink)) {
            self::connect();
        }
        if (is_null($str))
            return "NULL";
        if (is_bool($str))
            return ($str ? 1 : 0);
        return "'%" . self::$dblink->real_escape_string(str_replace(" ", "%", $str)) . "%'";
    }

    public static function query($query)
    {
        $QueryStartTime = \microtime(true);
        if (!isset(self::$dblink)) {
            self::connect();
        }
        for ($i = 1; $i < 6; $i++) {
            self::$res = self::$dblink->query($query);
            if (!in_array(self::$dblink->errno, array(1213, 1205))) {
                break;
            }
            sleep($i * rand(2, 5)); // Wait longer as attempts increase
        }
        $QueryEndTime = microtime(true);
        self::$time += ($QueryEndTime - $QueryStartTime) * 1000;
        $Errno = null;
        $Error = null;
        if (!self::$res) {
            $Errno = self::$dblink->errno;
            $Error = self::$dblink->error;

        }

        self::$query[] = array($query, ($QueryEndTime - $QueryStartTime) * 1000, self::$res, $Errno, $Error);
        self::$id++;

    }

    public static function initmultiquery($host, $login, $pass, $query)
    {
        $QueryStartTime = \microtime(true);
        if (!isset(self::$dblink)) {
            self::connect($host, $login, $pass);
        }
        for ($i = 1; $i < 6; $i++) {
            self::$res = self::$dblink->multi_query($query);
            if (!in_array(self::$dblink->errno, array(1213, 1205))) {
                break;
            }
            sleep($i * rand(2, 5)); // Wait longer as attempts increase
        }
        $QueryEndTime = microtime(true);
        self::$time += ($QueryEndTime - $QueryStartTime) * 1000;
        $Errno = null;
        $Error = null;
        if (!self::$res) {
            $Errno = self::$dblink->errno;
            $Error = self::$dblink->error;

        }

        self::$query[] = array($query, ($QueryEndTime - $QueryStartTime) * 1000, self::$res, $Errno, $Error);
        self::$id++;

    }

    public static function startTransaction()
    {
        if (!isset(self::$dblink)) {
            self::connect();
        }
        self::$dblink->autocommit(FALSE);

    }

    public static function endTransaction()
    {
        if (isset(self::$dblink)) {
            self::$dblink->commit();
        }


    }

    public static function nombreDeLigneAffecte()
    {
        return self::$dblink->affected_rows;
    }

    public static function getObject($className)
    {
        $tab = false;
        while ($Row = self::$res->fetch_object($className)) {
            $tab[] = $Row;
        }

        if (self::$res->num_rows == 1) {
            return $tab[0];
        }
        return $tab;
    }

    public static function getObjectAndClose($forcearray = false, $className = null)
    {
        $tab = false;
        if (self::$res) {
            if ($className != null) {

                while ($Row = self::$res->fetch_object($className)) {
                    $tab[] = $Row;
                }
            } else {
                while ($Row = self::$res->fetch_object()) {
                    $tab[] = $Row;
                }
            }

            if (self::$res->num_rows == 1 && !$forcearray) {
                $tab = $tab[0];
            }
            self::$res->free();
        }
        self::close();
        self::$query[self::$id - 1][2] = $tab;
        return $tab;
    }

    public static function close()
    {
        if (self::$dblink) {
            if (!self::$dblink->close()) {
                trigger_error("Impossible d'arrêté la connexion avec la base de données");
            }
            self::$dblink = null;
        }
    }
}