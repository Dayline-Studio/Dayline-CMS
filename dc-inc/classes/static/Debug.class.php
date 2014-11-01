<?php

class Debug
{

    private static $log_path = '/log/';
    private static $log_file_sql = 'sql_error.txt';
    private static $log_file = 'log.txt';

    private static $init = false;

    public static function log($str, $type = "", $sql = "")
    {
        self::init();
        switch ($type) {
            case 'PDO':
                $error = $str->errorInfo();
                if (!empty($error[2])) {
                    $log = $error[2];
                    self::write_log($log . ' -> ' . $sql, self::$log_file_sql);
                }
                break;
            default:
                self::write_log(print_r($str, true), self::$log_file);
        }
    }

    private static function write_log($str, $file = 'log.txt')
    {
        if (is_writable($file) || !file_exists($file)) {
            $file = self::$log_path . $file;
            $handle = fopen($file, "a+");
            fseek($handle, -3, SEEK_END);
            $str = '[' . date("Y-m-d H:i:s", time()) . '] ' . $str;
            fwrite($handle, $str . PHP_EOL);
            fclose($handle);
        }
    }

    public static function init()
    {
        if (!self::$init) {
            if (defined('DEBUG_LOG_PATH')) {
                self::$log_path = DEBUG_LOG_PATH;
            }
            if (defined('ENABLE_PHP_ERRORS') && ENABLE_PHP_ERRORS) {
                ini_set('display_startup_errors', 1);
                ini_set('display_errors', 1);
                error_reporting(-1);
            }
            self::$init = true;
        }
    }
}