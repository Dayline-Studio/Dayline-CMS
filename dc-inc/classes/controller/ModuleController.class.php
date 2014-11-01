<?php

class ModuleController
{

    private $position;
    public $modules;

    public static function scanModules($str) {
        $positions = self::searchBetween('{position_', $str, '}');
        $case = [];
        foreach ($positions as $position) {
            $modules = new ModuleController($position);
            $case['position_' . $position] = $modules->get_render();
        }
        return show($str, $case);
    }

    private static function searchBetween($start_tag, $String, $end_tag)
    {
        if (preg_match_all('/' . preg_quote($start_tag) . '(.*?)' . preg_quote($end_tag) . '/s', $String, $matches)) {
            return $matches[1];
        }
        return array();
    }

    public function __construct($position)
    {
        $this->position = $position;
        $this->read_modules();
    }

    private function read_modules()
    {
        $modules = Db::query('SELECT id, module FROM modules WHERE position LIKE :position ORDER BY order_pos', array('position' => $this->position), PDO::FETCH_OBJ);
        foreach ($modules as $module) {
            $this->modules[] = new $module->module($module->id);
        }
    }

    public function get_render()
    {
        $render = '';
        foreach ((array)$this->modules as $module) {
            $render .= $module->full_render();
        }
        if (permTo('site_edit') & !$_SESSION['prev_mode']) {
            $arr = $this->get_available_modules_list();
            $options = '';
            foreach ($arr as $value) {
                $options .= '<option value="' . $value->class . '">' . "$value->title</option>";
            }
            return show('site/modules_case', array('modules' => $render, 'position' => $this->position, 'available_modules' => $options));
        }
       return $render;
    }

    private static $modules_list;

    private function get_available_modules_list()
    {
        if (ModuleController::$modules_list == NULL) {
            $path = Config::$path['modules-info'];
            $handle = scandir($path);
            $list = array();
            foreach ($handle as $file) {
                if (substr($file, -4) == '.xml') {
                    $xml = simplexml_load_file($path . $file);
                    $list[] = $xml;
                }
            }
            ModuleController::$modules_list = $list;
        }
        return ModuleController::$modules_list;
    }
}