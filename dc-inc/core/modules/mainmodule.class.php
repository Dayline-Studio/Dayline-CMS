<?php

abstract class MainModule
{

    protected $id, $position, $order_pos, $render, $render_admin, $javaClass = 'Module';

    public function __construct($id, $create = false)
    {
        if ($create) {
            $id = str_replace('position_', '', $id);
            $this->position = $id;
            $i['position'] = $id;
            $i['module'] = get_class($this);
            Db::insert("modules", $i);
            $this->id = Db::get_last_id();
            $this->set_order_position();
            $this->on_construct();
            $this->update();
        } else {
            $this->id = $id;
        }
        $this->load_setup();
        $this->on_construct();
    }

    protected function on_create() {}
    protected function on_construct() {}

    public function load_setup()
    {
        $setup = Db::npquery("SELECT params,position,order_pos FROM modules WHERE id = " . $this->id . " LIMIT 1", PDO::FETCH_OBJ);
        if ($params = json_decode($setup->params)) {
            foreach ($params as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->position = $setup->position;
        $this->order_pos = $setup->order_pos;
    }

    protected abstract function render();

    protected abstract function render_admin();

    protected function get_max_modules_count()
    {
        return isset($this->max_count) ? $this->max_count : -1;
    }

    public function get_render($force_user = 0)
    {
        if ($this->render === NULL & !$force_user) {
            $this->render = $this->render();
        }
        return $this->set_infos($this->render);
    }

    public function get_render_admin($force_user = 0)
    {
        if ($this->render_admin === NULL & !$force_user) {
            $this->render_admin = $this->render_admin();
        }
        return $this->set_infos($this->render_admin);
    }

    public function full_render($force_user = 0)
    {
        if (permTo('site_edit') & !$_SESSION['prev_mode'] & !$force_user) {
            $file = 'site/module_box_admin';
            $case['module_render_admin'] = $this->get_render_admin();
        } else {
            $file = 'site/module_box';
        }
        $case['module_render'] = $this->get_render();
        return $this->set_infos(show($file, $case));
    }

    protected function set_infos($str)
    {
        $case['id'] = $this->id;
        $case['module_name'] = get_class($this);
        $case['javaClass'] = $this->javaClass;
        return Disp::replace_paths(show($str, $case));
    }

    public function set_vars($data = array())
    {
        foreach ($data as $var => $value) {
            if (isset($this->$var)) {
                $this->$var = $value;
            }
        }
    }

    public function update()
    {
        Db::update('modules', $this->id, array('params' => json_encode(get_public_properties($this)), 'order_pos' => $this->order_pos));
    }

    public function delete()
    {
        Db::delete('modules', $this->id);
        unset($this);
    }

    private function set_order_position()
    {
        $module = Db::query('SELECT * FROM modules WHERE position LIKE :position ORDER BY order_pos DESC LIMIT 1', array('position' => $this->position), PDO::FETCH_OBJ);
        $this->order_pos = $module->order_pos + 1;
    }

    public function move($dir)
    {
        if ($dir == 'up') {
            $module = Db::query('SELECT * FROM modules WHERE position LIKE :position AND order_pos < :order_pos ORDER BY order_pos DESC LIMIT 1', array('position' => $this->position, 'order_pos' => $this->order_pos), PDO::FETCH_OBJ);
        } else {
            $module = Db::query('SELECT * FROM modules WHERE position LIKE :position AND order_pos > :order_pos ORDER BY order_pos ASC LIMIT 1', array('position' => $this->position, 'order_pos' => $this->order_pos), PDO::FETCH_OBJ);
        }
        if (!$module) {
            $return['error'] = 'Kann nicht weiter verschoben werden';
        } else {
            $module_obj = new $module->module($module->id);
            $new = $module_obj->order_pos;
            $module_obj->order_pos = $this->order_pos;
            $this->order_pos = $new;
            $module_obj->update();
            $this->update();
            $return['target_id'] = $module_obj->id;
            $return['target_content'] = $module_obj->full_render();
            $return['this_id'] = $this->id;
            $return['this_content'] = $this->full_render();
        }
        return json_encode($return);
    }

    public function json_render()
    {
        $return['id'] = $this->id;
        $return['type'] = get_class($this);
        $return['javaClass'] = $this->javaClass;
        $return['prev'] = $this->get_render();
        $return['set'] = $this->get_render_admin();
        $return['full'] = $this->full_render();
        $return = array_merge($this->additional_json, $return);
        return json_encode($return);
    }

    private $additional_json = [];

    public function add_json_output($name, $value) {
        $this->additional_json = array_merge($this->additional_json, array($name => $value));
    }

    protected function save_as_default()
    {

    }

    protected function check_max_count()
    {
        $position = $this->position;
        $module = get_class($this);
        $modules = Db::query("SELECT id FROM modules WHERE position like '$position' and module LIKE '$module'");
        if (sizeof($modules) > $this->get_max_modules_count()) {
            return false;
        } else return true;
    }
}