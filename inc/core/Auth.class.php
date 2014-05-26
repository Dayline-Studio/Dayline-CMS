<?php

class Auth {
    
    public static function checkStatus() {
        self::check_header();
        //self::filter_php_ending();

        session_start();
        if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
                           $_SESSION['loggedin'] = false;
                           $_SESSION['user'] = 'gast';
                           $_SESSION['name'] = 'Gast';
                           $_SESSION['group_main_id'] = '2';
                           $_SESSION['group'] = 'gast';
                           $_SESSION['userid'] = 0;
         }
        //ip
        if (!isset($_SESSION['current_ip'])) {
           $_SESSION['current_ip'] = $_SERVER['REMOTE_ADDR'];
        }

        //Last Site:
        if (isset($_SESSION['last_site'])) {
           $_SESSION['back_site'] = $_SESSION['last_site'];
        }
        if (isset($_SESSION['current_site'])) {
           $_SESSION['last_site'] = $_SESSION['current_site']; 
        }
        $_SESSION['current_site'] = self::getCurrentUrl();
    }
    
    private static function getCurrentUrl() {
        return ((empty($_SERVER['HTTPS'])) ? 'http' : 'https') . "://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
    public function login() {
        
    }
    
    public function logout() {
        
    }

    private function check_header() {
        if (Config::$settings->force_domain) {
            $https = (empty($_SERVER['HTTPS'])) ? 'http' : 'https';
            $host = $_SERVER['HTTP_HOST'];
            if (Config::$settings->force_https && $https == 'http') {
                $https = 'https';
            }
            if ($host != Config::$settings->domain) {
                header('Location: '. $https . '://' . Config::$settings->domain . str_replace('.php','',$_SERVER['REQUEST_URI']));
            }
        }
    }

    private function filter_php_ending() {
        if (strpos($_SERVER['REQUEST_URI'],'.php')) {
            header('Location: '.((empty($_SERVER['HTTPS'])) ? 'http' : 'https') . "://". $_SERVER['HTTP_HOST'] . str_replace('.php','',$_SERVER['REQUEST_URI']));
        }
    }
}