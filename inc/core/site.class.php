<?php
class Site {

    public $id = NULL, $title, $userid, $keywords, $description, $subfrom, $position, $lastedit, $editby, $date;
    public $show_lastedit, $show_author, $show_print, $show_headline, $show_socialbar;
    protected $modules;

    public function __construct($data) {
        $this->set($data);
    }

    public function get_module($id) {
        return $this->modules[$id];
    }

    public function modules_load() {
        $modules = Db::npquery("SELECT id, module FROM modules WHERE position LIKE 'site-".$this->id."'", PDO::FETCH_OBJ);
        foreach ($modules as $module) {
            $this->modules[$module->id] = new $module->module($module->id);
        }
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
        Db::update('sites',$this->id,get_public_properties($this));
    }

    public function get_site_id() {
        return $this->id.'-'.str_replace(array(' ', '/', '.', '+'),'-', $this->title);
    }

    public function delete() {
        return Db::delete('sites', $this->id);
    }

    public function set($arr) {
        foreach ($arr as $tag => $value) {
            if (property_exists($this,$tag)) {
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