<?php

class Disp
{

    public static $meta = array();
    public static $content = "";

    public static function addMeta($meta)
    {
        if (is_array($meta)) {
            self::$meta = array_merge(self::$meta, $meta);
        }
    }

    public static function render()
    {
        $disp = show('index');
        $init = show($disp, array_merge(array("content" => self::$content),self::loadingPanels($disp)));
        $init = show($init, self::convertMatchDyn(searchBetween("{dyn_", $init, "}")));

        self::$meta['google_analytics'] = Config::$settings->google_analytics;
        self::$meta['domain'] = $_SERVER['HTTP_HOST'];
        self::$meta['title'] .= ' | ' . Config::$settings->website_title;

        self::$meta = array_merge(Config::$path, self::$meta);

        if (!isset(self::$meta['google_plus'])) {
            self::$meta['google_plus'] = "";
        }

        $sn = !empty($_SESSION['simple_note']) ? $_SESSION['simple_note'] : '';
        $init = show($init, array('simple_note' => $sn));
        $_SESSION['simple_note'] = NULL;

        $init = self::read_modules($init);
        $init = self::replace_paths($init);
        self::display($init);
    }

    public static function replace_paths($init)
    {
        self::$meta = array_merge(Config::$path, self::$meta);
        $init = show($init, self::$meta);
        $init = show($init, self::convertMatchDyn(searchBetween("{dyn_", $init, "}")));
        $init = show($init, convertMatch(searchBetween("{s_", $init, "}")));
        //$init = preg_replace("/\{\w\}/", "", $init);
        return $init;
    }

    public static function renderMin()
    {
        $init = self::replace_paths(self::$content);
        $sn = !empty($_SESSION['simple_note']) ? $_SESSION['simple_note'] : '';
        $init = show($init, array('simple_note' => $sn));
        self::display($init);
    }

    public static function renderMinStyle($index = '../templates/default/index.html')
    {
        self::$meta = array_merge(Config::$path, self::$meta);

        self::$meta['conten'] = self::$content;
        $meta = array_merge(self::$meta, array('content' => self::$content));
        $init = show($index, $meta);
        $sn = !empty($_SESSION['simple_note']) ? $_SESSION['simple_note'] : '';
        $init = show($init, array('simple_note' => $sn));
        //$init = preg_replace("/\{\w\}/", "", $init);
        self::display($init);
    }

    private static function display($content)
    {
        echo $content;
    }

    private static function loadingPanels($disp)
    {
        $panels = searchBetween('{panel_', $disp, '}');
        $new = array();
        foreach ($panels as $panel) {
            $panel = 'panel_'.$panel;
            $arr = explode('_',$panel);
            $name = $arr[1];
            $mode = isset($arr[2]) ? $arr[2] : 1;
            $file = Config::$path['panels'] . $name.'.php';
            if (file_exists($file)) {
                include ($file);
                if (function_exists($name)) {
                    $new[$panel] = $name($mode);
                } else {
                    $new[$panel] = 'Loading Panel failed';
                }
            } else {
                $new[$panel] = 'Panel file not found - '.$file;
            }
        }
        return $new;
    }

    private static function convertMatchDyn($matches)
    {
        $new = array();
        foreach ($matches as $value) {
            $new["dyn_" . $value] = show("allround/ajax_loading", array('panel_name' => $value));
        }
        return $new;
    }

    public static function read_modules($init) {
        $positions = searchBetween('{position_',$init,'}');
        $case = [];
        foreach ($positions as $position) {
            $modules = new ModuleManager($position);
            $case['position_'.$position] = $modules->get_render();
        }
        return show($init, $case);
    }

}
