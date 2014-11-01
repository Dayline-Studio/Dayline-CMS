<?php

class Display
{


    private $meta = array();
    public $content;

    public function __construct($meta = array())
    {
        $this->addMeta($meta);
    }

    public function addMeta($meta)
    {
        if (is_array($meta)) {
            $this->meta = array_merge($this->meta, $meta);
        }
    }

    public function render()
    {
        $myStorage = new StorageController(Config::$path['storage'], Config::$path['storage_rel']);
        $template = $myStorage->getTemplate(Config::$settings->style);
        $this->meta['stylesheets'] = $template->getStylesheets();

        $disp = $template->getFile('index');

        $init = show($disp, array_merge(array("content" => $this->content), $this->loadingPanels($disp)));

        $this->meta['google_analytics'] = Config::$settings->google_analytics;
        $this->meta['domain'] = $_SERVER['HTTP_HOST'];
        $this->meta['title'] .= ' | ' . Config::$settings->website_title;

        $this->meta = array_merge(Config::$path, $this->meta);

        if (!isset($this->meta['google_plus'])) {
            $this->meta['google_plus'] = "";
        }

        $sn = !empty($_SESSION['simple_note']) ? $_SESSION['simple_note'] : '';
        $init = show($init, array('simple_note' => $sn));
        $_SESSION['simple_note'] = NULL;

        $init = ModuleController::scanModules($init);
        $init = $this->replace_paths($init);

        $this->display($init);
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    private function convertMatch($matches)
    {
        $new = array();
        foreach ($matches as $value) {
            if (defined("_" . $value)) {
                $new["s_" . $value] = constant("_" . $value);
            } else {
                $new["s_" . $value] = "STRING_NOT_FOUND_" . strtoupper($value);
            }
        }
        return $new;
    }

    private function searchBetween($start_tag, $String, $end_tag)
    {
        if (preg_match_all('/' . preg_quote($start_tag) . '(.*?)' . preg_quote($end_tag) . '/s', $String, $matches)) {
            return $matches[1];
        }
        return array();
    }

    public function replace_paths($init)
    {
        $this->meta = array_merge(Config::$path, $this->meta);
        $init = show($init, $this->meta);
        $init = show($init, $this->convertMatch($this->searchBetween("{s_", $init, "}")));
        return $init;
    }

    private function display($content)
    {
        echo $content;
    }

    private function loadingPanels($disp)
    {
        $panels = $this->searchBetween('{panel_', $disp, '}');
        $new = array();
        foreach ($panels as $panel) {
            $panel = 'panel_' . $panel;
            $arr = explode('_', $panel);
            $name = $arr[1];
            $mode = isset($arr[2]) ? $arr[2] : 1;
            $file = Config::$path['panels'] . $name . '.php';
            if (file_exists($file)) {
                include($file);
                if (function_exists($name)) {
                    $new[$panel] = $name($mode);
                } else {
                    $new[$panel] = 'Loading Panel failed';
                }
            } else {
                $new[$panel] = 'Panel file not found - ' . $file;
            }
        }
        return $new;
    }

}
