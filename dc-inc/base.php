<?php
$installation = false;
if (file_exists("../dc-storage/config.php")) {
    include("../dc-storage/config.php");
}

$GLOBALS['base_dir'] = realpath(dirname(__FILE__).'/../');

spl_autoload_register(null, false);
spl_autoload_extensions('.class.php');
spl_autoload_register('classLoader');

function classLoader($class)
{
    $classname = strtolower($class);
    $filename = $classname . '.class.php';
    $path['core'] = $GLOBALS['base_dir'].'/dc-inc/core/';
    $path['lib'] = $GLOBALS['base_dir'].'/dc-inc/lib/';

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


include(Config::$path['functions']);
Auth::checkStatus();

if (isset($_REQUEST['show'])) {
    $show = $_REQUEST['show'];
} else {
    $show = "";
}
if (isset($_REQUEST['do'])) {
    $do = $_REQUEST['do'];
} else {
    $do = "";
}
if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
} else {
    $action = "";
}