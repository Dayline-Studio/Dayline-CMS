<?php

class Auth
{

    /**
     * Checking Session status
     *
     * @since 0.4
     */
    public static function checkStatus()
    {
        self::check_header();

        session_start();
        if (!isset($_SESSION['loggedin']) || !$_SESSION['loggedin']) {
            $_SESSION['loggedin'] = false;
            $_SESSION['user'] = 'gast';
            $_SESSION['name'] = 'Gast';
            $_SESSION['group_main_id'] = '2';
            $_SESSION['group'] = 'gast';
            $_SESSION['userid'] = 0;
        }

        if (!isset($_SESSION['current_ip'])) {
            $_SESSION['current_ip'] = $_SERVER['REMOTE_ADDR'];
        }

        if (isset($_SESSION['last_site'])) {
            $_SESSION['back_site'] = $_SESSION['last_site'];
        }
        if (isset($_SESSION['current_site'])) {
            $_SESSION['last_site'] = $_SESSION['current_site'];
        }
        $_SESSION['current_site'] = self::getCurrentUrl();
    }

    /**
     * gibt aktuelle URL zurück
     *
     * @since 0.4
     * @return string
     */
    private static function getCurrentUrl()
    {
        return ((empty($_SERVER['HTTPS'])) ? 'http' : 'https') . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    }

    /**
     * User logged out
     * @since 0.4
     */
    public static function logout()
    {
        session_destroy();
    }

    private static function check_header()
    {
        if (Config::$settings->force_domain) {
            $https = (empty($_SERVER['HTTPS'])) ? 'http' : 'https';
            $host = $_SERVER['HTTP_HOST'];
            if (Config::$settings->force_https && $https == 'http') {
                $https = 'https';
            }
            if ($host != Config::$settings->domain) {
                header('Location: ' . $https . '://' . Config::$settings->domain . str_replace('.php', '', $_SERVER['REQUEST_URI']));
            }
        }
    }

    /**
     * @param $user User Object for User Data
     * @since 0.4
     */
    private static function create_session($user)
    {
        $_SESSION['loggedin'] = true;
        $_SESSION['name'] = $user->name;
        $_SESSION['user'] = $user->user;
        $_SESSION['userid'] = $user->id;
        $_SESSION['email'] = $user->email;
        $_SESSION['login_time'] = time();
        $_SESSION['https'] = Config::$settings->foce_https ? TRUE : FALSE;
        $_SESSION['group_main_id'] = $user->main_group;
        if (permTo('fm_access')) $_SESSION['fm_access'] = TRUE;
    }

    /**
     * @since 0.4
     * @param string $username username
     * @param string $password user password
     * @return bool returns true if the session is created successful
     */
    public static function login($username, $password)
    {
        if ($username != "" && $password != "") {
            if (!check_email_address($username)) {
                $sql = "user";
            } else {
                $sql = "email";
            }
            if (db("SELECT id FROM users WHERE " . $sql . " LIKE " . sqlString(strtolower($_POST['username'])), 'rows') == 1) {

                $user = db('Select * From users Where ' . $sql . ' like ' . sqlString(strtolower($username)), 'object');

                if (customHasher($password, $user->rounds) == $user->pass) {
                    self::create_session($user);
                    return true;
                }
            }
        }
        return false;
    }
}