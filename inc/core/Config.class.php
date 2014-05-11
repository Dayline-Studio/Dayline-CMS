<?php
class Config {
    
    public static $sql = array();
    public static $path = array();
    public static $template = array();
    public static $settings;
    
    function init() {

        self::$sql = array(
            'host' => 'localhost',
            'user' => 'db31',
            'db' => 'usr_db31_3',
            'pw' => 'daY6LAFfUWyhrrNG',
            'salt' => 'hejkw7je5n3k0ab2',
        );
    }
    
    function loadSettings() {
        //Default settings
        self::$settings['lang'] = 'de';
        self::$settings['style'] = 'default';

        //Loading Settings from Database
        self::$settings = Db::npquery('SELECT * FROM settings LIMIT 1', PDO::FETCH_OBJ);
        self::$path = array(
            'dir' => './',
            'content' => "../content/",
            'upload' => '../include/upload/',
            'images' => '../include/images/',
            'plugins' => '../content/plugins/',
            'pages' => '../pages/',
            'panels' => '../content/panels/',
            'funktions' => '../include/functions.php',
            'language' => '../content/language/',
            'template' => '../templates/'.self::$settings->style.'/'
        );

        self::$template = array(
            'index' => self::$path['template'].'index.html'
        );
    }
    
    function loadLanguage() {

        include(self::$path['language']."global.php");

        if (isset($_GET['l'])) { $language = $_GET['l']; } 
        else { $language = "de"; }
        
        if ( $language != 'de' || $language != 'en') {
               $language = self::$settings->language;
        }
     
        $lang_dir = opendir(self::$path['language'].$language);
        while ($lang_file = readdir($lang_dir)) {
                if ($lang_file != ".." && $lang_file != ".") {
                        include(self::$path['language'].$language.'/'.$lang_file);
                }
        } 	  
        closedir($lang_dir);
    }
}