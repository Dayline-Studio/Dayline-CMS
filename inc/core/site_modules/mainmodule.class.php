<?php

abstract class MainModule {

    public $id;

    public function __construct($id, $create = false) {
        if ($create) {
            $i['position'] = $id;
            $i['module'] = get_class($this);
            Db::insert("modules", $i);
            $this->id = Db::get_last_id();
        } else {
            $this->id = $id;
        }
        $this->load_setup();
    }

    public function load_setup() {
        $setup = Db::npquery("SELECT params FROM modules WHERE id = ".$this->id, PDO::FETCH_OBJ);
        if ($params = json_decode($setup[0]->params)) {
            foreach ($params as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    public abstract function render();

    public abstract function render_admin();

    public function full_render() {
        if ($_SESSION['userid'] == 1) {
            $file = 'site/module_box_admin';
            $case['module_render_admin'] = $this->render_admin();
        } else {
            $file = 'site/module_box';
        }
        $case['module_render'] = $this->render();
        $case['id'] = $this->id;
        $case['module_name'] = get_class($this);
        return show($file, $case);
    }

    public function set_vars($data = array()) {
        foreach ($data as $var => $value) {
            if (isset($this->$var)) {
                $this->$var = $value;
            }
        }
    }

    public function update() {
        Db::update('modules',$this->id, array('params' => json_encode(get_object_vars($this))));
    }

    public function delete() {
        Db::delete('modules',$this->id);
        unset($this);
    }
}