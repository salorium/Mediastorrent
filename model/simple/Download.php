<?php
/**
 * Created by PhpStorm.
 * User: salorium
 * Date: 01/04/14
 * Time: 09:32
 */

namespace model\simple;


use core\Model;

class Download extends Model
{
    static function sendFile($file)
    {
        if (!file_exists($file)) {
            throw new \Exception("FILE NOT FOUND");
            exit;
        }
        //var_dump($_SERVER);
        //die();
// Get the 'Range' header if one was sent
        if (isset($_SERVER['HTTP_RANGE'])) $range = $_SERVER['HTTP_RANGE']; // IIS/Some Apache versions
        /*else if ($apache = \getallheaders()) { // Try Apache again
            var_dump($_SERVER);
            die();
            $headers = array();
            foreach ($apache as $header => $val) $headers[strtolower($header)] = $val;
            if (isset($headers['range'])) $range = $headers['range'];
            else $range = FALSE; // We can't get the header/there isn't one set
        }*/
        else $range = FALSE; // We can't get the header/there isn't one set

// Get the data range requested (if any)
        $length = $filesize = filesize($file);
        if ($range) {
            $partial = true;
            list($param, $range) = explode('=', $range);
            if (strtolower(trim($param)) != 'bytes') { // Bad request - range unit is not 'bytes'
                header("HTTP/1.1 400 Invalid Request");
                exit;
            }
            $range = explode(',', $range);
            $range = explode('-', $range[0]); // We only deal with the first requested range
            if (count($range) != 2) { // Bad request - 'bytes' parameter is not valid
                header("HTTP/1.1 400 Invalid Request");
                exit;
            }
            if ($range[0] === '') { // First number missing, return last $range[1] bytes
                $end = $filesize - 1;
                $start = $end - intval($range[0]);
            } else if ($range[1] === '') { // Second number missing, return from byte $range[0] to end
                $start = intval($range[0]);
                $end = $filesize - 1;
            } else { // Both numbers present, return specific range
                $start = intval($range[0]);
                $end = intval($range[1]);
                if ($end >= $filesize || (!$start && (!$end || $end == ($filesize - 1)))) $partial = false; // Invalid range/whole file specified, return whole file
            }
            $length = $end - $start + 1;
        } else $partial = false; // No range requested

// Send standard headers
        header("Content-Type: " . mime_content_type($file));
        header("Content-Length: $filesize");
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Accept-Ranges: bytes');

// if requested, send extra headers and part of file...
        if ($partial) {
            header('HTTP/1.1 206 Partial Content');
            header("Content-Range: bytes $start-$end/$filesize");
        } else {
            $start = 0;
        }
        if (!$fp = fopen($file, 'r')) { // Error out if we can't read the file
            header("HTTP/1.1 403 Forbidden");
            exit;
        }
        if ($start) fseek($fp, $start);
        while ($length) { // Read in blocks of 8KB so we don't chew up memory on the server
            $read = ($length > 8192) ? 8192 : $length;
            $length -= $read;
            print(fread($fp, $read));
        }
        fclose($fp);


        /* else {
            echo "ddd";
            die();
            readfile($file); // ...otherwise just send the whole file
        }*/
        exit;
    }

    static function sendFileName($file, $name)
    {
        if (!file_exists($file)) {
            throw new \Exception("FILE NOT FOUND");
            exit;
        }
        //var_dump($_SERVER);
        //die();
// Get the 'Range' header if one was sent
        if (isset($_SERVER['HTTP_RANGE'])) $range = $_SERVER['HTTP_RANGE']; // IIS/Some Apache versions
        /*else if ($apache = \getallheaders()) { // Try Apache again
            var_dump($_SERVER);
            die();
            $headers = array();
            foreach ($apache as $header => $val) $headers[strtolower($header)] = $val;
            if (isset($headers['range'])) $range = $headers['range'];
            else $range = FALSE; // We can't get the header/there isn't one set
        }*/
        else $range = FALSE; // We can't get the header/there isn't one set

// Get the data range requested (if any)
        $length = $filesize = filesize($file);
        if ($range) {
            $partial = true;
            list($param, $range) = explode('=', $range);
            if (strtolower(trim($param)) != 'bytes') { // Bad request - range unit is not 'bytes'
                header("HTTP/1.1 400 Invalid Request");
                exit;
            }
            $range = explode(',', $range);
            $range = explode('-', $range[0]); // We only deal with the first requested range
            if (count($range) != 2) { // Bad request - 'bytes' parameter is not valid
                header("HTTP/1.1 400 Invalid Request");
                exit;
            }
            if ($range[0] === '') { // First number missing, return last $range[1] bytes
                $end = $filesize - 1;
                $start = $end - intval($range[0]);
            } else if ($range[1] === '') { // Second number missing, return from byte $range[0] to end
                $start = intval($range[0]);
                $end = $filesize - 1;
            } else { // Both numbers present, return specific range
                $start = intval($range[0]);
                $end = intval($range[1]);
                if ($end >= $filesize || (!$start && (!$end || $end == ($filesize - 1)))) $partial = false; // Invalid range/whole file specified, return whole file
            }
            $length = $end - $start + 1;
        } else $partial = false; // No range requested

// Send standard headers
        header("Content-Type: " . mime_content_type($file) . "; charset=utf-8");
        header("Content-Length: $filesize");
        header('Content-Disposition: attachment; filename="' . (str_replace('"', '\"', str_replace("&lt;", "<", $name))) . '.' . pathinfo($file, PATHINFO_EXTENSION) . '"');
        header('Accept-Ranges: bytes');

// if requested, send extra headers and part of file...
        if ($partial) {
            header('HTTP/1.1 206 Partial Content');
            header("Content-Range: bytes $start-$end/$filesize");
        } else {
            $start = 0;
        }
        if (!$fp = fopen($file, 'r')) { // Error out if we can't read the file
            header("HTTP/1.1 403 Forbidden");
            exit;
        }
        if ($start) fseek($fp, $start);
        while ($length) { // Read in blocks of 8KB so we don't chew up memory on the server
            $read = ($length > 8192) ? 8192 : $length;
            $length -= $read;
            print(fread($fp, $read));
        }
        fclose($fp);


        /* else {
            echo "ddd";
            die();
            readfile($file); // ...otherwise just send the whole file
        }*/
        exit;
    }

} 