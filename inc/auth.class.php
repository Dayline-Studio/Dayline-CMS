<?php
    session_start();

    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
                       $_SESSION['loggedin'] = false;
                       $_SESSION['user'] = 'gast';
                       $_SESSION['name'] = 'Gast';
                       $_SESSION['group_main_id'] = '0';
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
         $_SESSION['current_site'] = getCurrentUrl();

       function getCurrentUrl() {
               return ((empty($_SERVER['HTTPS'])) ? 'http' : 'https') . "://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }
    
   /* $_SESSION['page_count'] = 0;
    //$_SESSION['page_last_refresh'] = 0;
    
    if (time()-$_SESSION['page_last_refresh'] < 60 &&  $_SESSION['page_count'] < 100)
    {
        $_SESSION['page_count']++;
    }
    else die("Session Banned");
    if (time()-$_SESSION['page_last_refresh'] > 60 &&  $_SESSION['page_count'] < 100)
    {
        $_SESSION['page_last_refresh'] = time();
        $_SESSION['page_count'] = 0;
    } */