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

        self::$path = array(
            'dir' => './',
            'content' => "../dc-content/",
            'cache' => "../dc-storage/_cache/",
            'log' => "../dc-storage/_log/",
            'upload' => '../dc-storage/_upload/',
            'images' => '../dc-inc/images/',
            'plugins' => '../dc-content/plugins/',
            'thumbs' => '../dc-storage/_thumbs/',
            'pages' => '../pages/',
            'rss' => '../dc-storage/_rss/',
            'panels' => '../dc-inc/panels/',
            'panels_dyn' => '../dc-inc/panels_dyn/',
            'modules-info' => '../dc-inc/modules/',
            'acp' => '../dc-acp/',
            'functions' => '../dc-inc/functions.php',
            'filemanager' => '../dc-content/plugins/filemanager/dialog.php',
            'gallery' => '../dc-storage/_upload/gallery/',
            'language' => '../dc-content/language/',
            'template' => '../dc-templates/' . self::$settings->style . '/',
            'template_default' => '../dc-templates/default/',
            'fw_js' => '../dc-inc/framework/js/',
            'fw_css' => '../dc-inc/framework/css/'
        );

        self::$path['css'] = self::$path['template'] . '_css/';
        self::$path['js'] = self::$path['template'] . '_js/';
        self::$path['style'] = self::$path['template'];

        self::$template = array(
            'index' => self::$path['template'] . 'index.html'
        );
    }

    public static function set_settings($settings) {
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