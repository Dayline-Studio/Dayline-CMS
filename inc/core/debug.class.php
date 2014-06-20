<?php

class Debug {

    private static $log_path = '../inc/_log/';
    private static $log_file_sql = 'sql_error.txt';
    private static $log_file = 'log.txt';

    public static function log($str, $type, $sql = "") {
        switch ($type) {
            case 'PDO':
                $error = $str->errorInfo();
                if (!empty($error[2])) {
                    $log = $error[2];
                    self::write_log($log.' -> '.$sql, self::$log_file_sql);
                }
                break;
            default:
                self::write_log(print_r($str,true), self::$log_file);
        }
    }

    private static function write_log($str, $file = 'log.txt') {
        $file = self::$log_path.$file;
        $handle = fopen($file, "a+");
        fseek($handle, -3, SEEK_END);
        $str = '['.date("Y-m-d H:i:s", time()).'] '.$str;
        if (!fwrite($handle, $str . PHP_EOL)) {
            die("keine Permissions auf $file");
            exit;
        }
        fclose($handle);
    }

}