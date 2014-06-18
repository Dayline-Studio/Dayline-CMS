<?php

abstract class MainModule {

    public $id;

    public function __construct($id) {
        $this->id = $id;
        $this->load_setup();
    }

    public function load_setup() {
        $setup = Db::npquery('SELECT * FROM mod_'.strtolower(get_class($this))." WHERE id = ".$this->id, PDO::FETCH_OBJ);
        foreach ($setup[0] as $key => $value) {
            $this->$key = $value;
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
}