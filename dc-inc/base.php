<?php
$installation = false;
if (file_exists("../dc-storage/config.php")) {
    include("../dc-storage/config.php");
}

$GLOBALS['base_dir'] = realpath(dirname(__FILE__) . '/../');

$myClassLoader = new dcAutoLoader();

class dcAutoLoader {

    private $path = [];

    public function __construct() {
        $this->path[] = $GLOBALS['base_dir'] . '/dc-inc/core/';
        $this->path[] = $GLOBALS['base_dir'] . '/dc-additional/core/';
        $this->path[] = $GLOBALS['base_dir'] . '/dc-inc/lib/';
        $this->path[] = $GLOBALS['base_dir'] . '/dc-additional/lib/';

        $this->setRegister($this->path[0]);
        $this-> setRegister($this->path[1]);

        $this->loadRegister();
    }

    private function loadRegister() {
        spl_autoload_register(function ($class)
        {
            $classname = strtolower($class);
            $filename = $classname . '.class.php';

            foreach ($this->path as $dir) {
                if (is_readable($dir . $filename)) {
                    include_once $dir . $filename;
                    return true;
                } else if (is_readable($dir . $classname . '/' . $classname . '.php')) {
                    include_once $dir . $classname . '/' . $classname . '.php';
                    return true;
                }
            }
            return false;
        });

    }

    private function setRegister($reg_path) {
        $handle = opendir($reg_path);
        while ($datei = readdir($handle)) {
            if (is_dir($reg_path . $datei) && $datei != '.' && $datei != '..') {
                $this->path[] = $reg_path . $datei . '/';
            }
        }
        closedir($handle);
    }
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

include_once(Config::$path['functions']);

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