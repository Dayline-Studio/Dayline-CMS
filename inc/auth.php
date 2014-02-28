<?php
    session_start();

    if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
                       $_SESSION['loggedin'] = false;
                       $_SESSION['user'] = 'gast';
                       $_SESSION['name'] = 'Gast';
                       $_SESSION['group'] = 'gast';
     }
         //Last Site:
         $_SESSION['back_site'] = $_SESSION['last_site'];
         $_SESSION['last_site'] = $_SESSION['current_site'];
         $_SESSION['current_site'] = getCurrentUrl();

       function getCurrentUrl() {
               return ((empty($_SERVER['HTTPS'])) ? 'http' : 'https') . "://". $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }