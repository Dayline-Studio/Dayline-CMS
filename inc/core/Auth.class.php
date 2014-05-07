<?php

class Auth {
    
    public static function checkStatus() {
        
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
    
    function login() {
        
    }
    
    function logout() {
        
    }    
}