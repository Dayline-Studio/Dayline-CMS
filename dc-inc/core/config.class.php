<?php

/**
 * Class Config Läd sämtliche configurationen wie Pfade, SQL-Zugang und Settings aus der Db.
 */
class Config
{

    public static $sql = array();
    public static $path = array();
    public static $template = array();
    public static $settings;

    /**
     * Initialisiert die SQL Daten um direkt eine verbidnung zu den Settings in der Datenbank aufzubauen.
     */
    public static function init()
    {
        global $config;
        if (isset($config)) {
            self::$sql = array(
                'host' => $config['sql_host'],
                'user' => $config['sql_user'],
                'db' => $config['sql_db'],
                'pw' => $config['sql_pass'],
                'salt' => $config['salt']
            );
        }
    }

    /**
     * Läd Settings aus der Ladenbank, setzt ebenso Pfade für Template, include und upload.
     */
    public static function loadSettings()
    {
        if (empty(self::$settings)) {
            self::$settings = new ArrayObject();
            self::$settings->force_domain = 0;
            self::$settings->force_https = 0;
            self::$settings->link_twitter = 'https://github.com/Dayline-Studio/CMS';
            self::$settings->link_facebook = 'https://github.com/Dayline-Studio/CMS';
            self::$settings->link_youtube = 'https://github.com/Dayline-Studio/CMS';
            self::$settings->link_google = 'https://github.com/Dayline-Studio/CMS';
        }
        self::$settings->language = 'de';
        if (isset($_GET['style'])) {
            self::$settings->style = $_GET['style'];
        }
        $base = $GLOBALS['base_dir'];
        $subfolder = ''; //Without end slash

        self::$path = array(
            'subfolder' => $subfolder,
            'content' => "../dc-content/",
            'cache' => "$base/dc-storage/_cache/",
            'pages' => "/",
            'log' => "$base/dc-storage/_log/",
            'upload_rel' => "$subfolder/dc-storage/_upload/",
            'upload' => "$base/dc-storage/_upload/",
            'images' => '../dc-inc/images/',
            'plugins' => "$subfolder/dc-content/plugins/",
            'thumbs' => "$base/dc-storage/_thumbs/",
            'thumbs_rel' => "$subfolder/dc-storage/_thumbs/",
            'rss' => '../dc-storage/_rss/',
            'panels' => "$base/dc-inc/panels/",
            'panels_dyn' => '../dc-inc/panels_dyn/',
            'modules-info' => "$base/dc-inc/modules/",
            'acp' => "$base/dc-acp/",
            'functions' => "$base/dc-inc/functions.php",
            'filemanager' => "$subfolder/dc-content/plugins/filemanager/dialog.php",
            'gallery' => "$subfolder/dc-storage/_upload/gallery/",
            'language' => "$base/dc-content/language/",
            'template' => "$subfolder/dc-templates/" . self::$settings->style . '/',
            'template_abs' => "$base/dc-templates/" . self::$settings->style . '/',
            'template_default' => "$base/dc-templates/default/",
            'fw_js' => "$subfolder/dc-inc/framework/js/",
            'fw_css' => "$subfolder/dc-inc/framework/css/"
        );

        self::$path['root_base'] = str_replace(self::$path['upload_rel'],'',self::$path['upload']);

        self::$path['css'] = self::$path['template'] . '_css/';
        self::$path['js'] = self::$path['template'] . '_js/';
        self::$path['style'] = self::$path['template'];

        self::$template = array(
            'index' => self::$path['template'] . 'index.html'
        );
    }

    public static function set_settings($settings)
    {
        self::$settings = $settings;
    }

    /**
     * Läd die Sprachdateien der angegebenen Sprache.
     */
    public static function loadLanguage()
    {

        include(self::$path['language'] . "global.php");

        if (isset($_GET['l'])) {
            $language = $_GET['l'];
        } else {
            $language = "de";
        }

        if ($language != 'de' || $language != 'en') {
            $language = self::$settings->language;
        }

        $lang_dir = opendir(self::$path['language'] . $language);
        while ($lang_file = readdir($lang_dir)) {
            if ($lang_file != ".." && $lang_file != ".") {
                include(self::$path['language'] . $language . '/' . $lang_file);
            }
        }
        closedir($lang_dir);
    }
}