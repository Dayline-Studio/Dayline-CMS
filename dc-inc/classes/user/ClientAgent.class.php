<?php

class ClientAgent
{

    public static $agent;
    public static $browser;
    public static $ip;
    public static $os;
    public static $location;

    public static function read()
    {
        self::$agent = $_SERVER['HTTP_USER_AGENT'];
        self::$ip = self::get_ip();
        self::$os = self::get_os();
        self::$browser = self::get_browser();
    }

    private static function get_ip()
    {
        return getenv('REMOTE_ADDR');
    }

    private static function get_os()
    {
        $list = self::get_os_list();
        foreach ($list as $search => $replace) {
            if (strstr(self::$agent, $search)) {
                return $replace;
            }
        }
        return 'Unknown';
    }

    private static function get_browser()
    {
        $list = self::get_browser_list();
        foreach ($list as $search => $replace) {
            if (strstr(self::$agent, $search)) {
                return $replace;
            }
        }
        return 'Unknown';
    }


    private static function get_os_list()
    {
        $os['Windows 98'] = 'Windows 98';
        $os['NT 4.0'] = 'Windows NT';
        $os['NT 5.1'] = 'Windows XP';
        $os['NT 6.0'] = 'Windows Vista';
        $os['NT 6.1'] = 'Windows 7';
        $os['NT 6.2'] = 'Windows 8';
        $os['NT 6.3'] = 'Windows 8.1';
        $os['NT'] = 'Windows (Unknown)';

        $os['Mac'] = 'Mac OS';
        $os['Linux'] = 'Linux';
        return $os;
    }

    private static function get_browser_list()
    {
        $br['Firefox/30'] = 'Firefox 30';
        $br['Firefox/29'] = 'Firefox 29';
        $br['Firefox/28'] = 'Firefox 28';
        $br['Firefox/27'] = 'Firefox 27';
        $br['Firefox/26'] = 'Firefox 26';
        $br['Firefox/25'] = 'Firefox 25';
        $br['Firefox/24'] = 'Firefox 24';
        $br['Firefox/23'] = 'Firefox 23';
        $br['Firefox'] = 'Firefox';
        $br['MSIE 7.0'] = 'Internet Explorer 7';
        $br['MSIE 8.0'] = 'Internet Explorer 8';
        $br['MSIE 9.0'] = 'Internet Explorer 9';
        $br['MSIE 10.0'] = 'Internet Explorer 10';
        $br['MSIE 11.0'] = 'Internet Explorer 11';
        $br['MSIE 12.0'] = 'Internet Explorer 12';
        $br['MSIE'] = 'Internet Explorer';

        $br['Netscape'] = 'Netscape';
        $br['Camino'] = 'Camino';
        $br['Galeon'] = 'Galeon';
        $br['Konqueror'] = 'Konqueror';
        $br['Safari'] = 'Safari';
        $br['OmniWeb'] = 'OmniWeb';
        $br['Opera'] = 'Opera';
        return $br;
    }

}