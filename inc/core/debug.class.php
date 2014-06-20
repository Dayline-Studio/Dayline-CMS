<?php

class Debug {

    private static $log_path = '../inc/_log/';

    public static function log($str) {
        $log = '['.date("Y-m-d H:i:s", time()).'] '.$str;
        self::write_log($log);
    }

    private static function write_log($str) {
        $file = self::$log_path.'log.txt';
        if (is_writable($file)) {
            $handle = fopen($file, "a");
            fseek($handle, -3, SEEK_END);
            if (!fwrite($handle, $str . PHP_EOL)) {
                die('keine Permissions auf log.txt');
                exit;
            }
            fclose($handle);
        } else {
            die("Die Datei $file ist nicht schreibbar der log kann somit nicht geschrieben werden!");
        }
    }

}