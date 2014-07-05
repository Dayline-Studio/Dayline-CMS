<?php

class Debug
{

    private static $log_path = '../dyn-content/_log/';
    private static $log_file_sql = 'sql_error.txt';
    private static $log_file = 'log.txt';

    public static function log($str, $type = "", $sql = "")
    {
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

}