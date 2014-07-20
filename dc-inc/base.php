<?php
$installation = false;
if (file_exists("../dc-storage/config.php")) {
    include("../dc-storage/config.php");
}

spl_autoload_register(null, false);
spl_autoload_extensions('.class.php');
spl_autoload_register('classLoader');

function classLoader($class)
{
    $classname = strtolower($class);
    $filename = $classname . '.class.php';
    $path['core'] = '../dc-inc/core/';
    $path['lib'] = '../dc-inc/lib/';

    $handle = opendir($path['core']);
    while ($datei = readdir($handle)) {
        if (is_dir($path['core'].$datei) && $datei != '.' || $datei != '..') {
            $path[$datei] = $path['core'].$datei.'/';
        }
    }
    closedir($handle);

    foreach ($path as $dir) {
        if (is_readable($dir . $filename)) {
            include $dir . $filename;
            return true;
        } else if (is_readable($dir . $classname . '/' . $classname . '.php')) {

            include $dir . $classname . '/' . $classname . '.php';
            return true;
        }
    }
    return false;
}

date_default_timezone_set('Europe/Berlin');
define('ENABLE_PHP_ERRORS', TRUE);
define('DEBUG_LOG_PATH', '../dc-storage/_log/');

Config::init();
Db::init(Config::$sql);
if ($set = Db::npquery('SELECT * FROM settings LIMIT 1', PDO::FETCH_OBJ)) {
    Config::set_settings($set);
} else {

}
Config::loadSettings();
Config::loadLanguage();
Auth::checkStatus();

//Parameter auslesen für allegemeine Settings
if (isset($_GET['show'])) {
    $show = $_GET['show'];
} else {
    $show = "";
}
if (isset($_GET['do'])) {
    $do = $_GET['do'];
} else {
    $do = "";
}
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = "";
}

include(Config::$path['functions']);