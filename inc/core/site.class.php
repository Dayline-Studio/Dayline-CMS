<?php
class Site {

    public $id = NULL, $title, $modules, $userid, $keywords, $description, $subfrom, $position, $lastedit, $editby, $date;
    public $show_lastedit, $show_author, $show_print, $show_headline, $show_socialbar;

    public function __construct($data) {
        foreach($data as $var => $value) {
            $this->$var = $value;
        }
    }

    public function modules_load() {
        $modules_loader = explode(';',$this->modules);
        $modules = NULL;
        foreach ($modules_loader as $module_load) {
            $z = explode('-',$module_load);
            $module_name = $z[0];
            $module_id = $z[1];
            $modules[strtolower($module_name).'_'.$module_id] = new $module_name($module_id);
        }
        $this->modules = $modules;
    }

    public function modules_render() {
        $this->modules_load();
        $render = '';
        foreach ($this->modules as $module) {
            $render .= $module->full_render();
        }
        return $render;
    }

    public function update() {
        Db::update('sites',$this->id,get_object_vars($this));
    }

    public function get_site_id(){
        return $this->id.'-'.str_replace(array(' ', '/', '.', '+'),'-', $this->title);
    }

    public function delete(){
        return Db::delete('sites', $this->id);
    }

    public function set($arr) {
        foreach ($arr as $tag => $value) {
            if (isset($this->$tag)) {
                $this->$tag = $value;
            }
        }
    }

    public function clear_checkboxes() {
        $this->show_author = 0;
        $this->show_headline = 0;
        $this->show_lastedit = 0;
        $this->show_print = 0;
        $this->show_socialbar = 0;
    }
}