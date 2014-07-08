<?php

abstract class MainModule
{

    public $id, $position, $order_pos;

    public function __construct($id, $create = false)
    {
        if ($create) {
            $this->position = $id;
            $i['position'] = $id;
            $i['module'] = get_class($this);
            Db::insert("modules", $i);
            $this->id = Db::get_last_id();
            $this->set_order_position();
            $this->update();
        } else {
            $this->id = $id;
        }
        $this->load_setup();
    }

    public function load_setup()
    {
        $setup = Db::npquery("SELECT params,position,order_pos FROM modules WHERE id = " . $this->id, PDO::FETCH_OBJ);
        if ($params = json_decode($setup[0]->params)) {
            foreach ($params as $key => $value) {
                $this->$key = $value;
            }
        }
        $this->position = $setup[0]->position;
        $this->order_pos = $setup[0]->order_pos;
    }

    public abstract function render();

    public abstract function render_admin();

    public function full_render()
    {
        if (permTo('site_edit') && !$_SESSION['prev_mode']) {
            $file = 'site/module_box_admin';
            $case['module_render_admin'] = $this->render_admin();
        } else {
            $file = 'site/module_box';
        }
        $case['module_render'] = $this->render();
        $case['id'] = $this->id;
        $case['module_name'] = get_class($this);
        return Disp::replace_paths(show($file, $case));
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
        Db::update('modules', $this->id, array('params' => json_encode(get_object_vars($this)), 'order_pos' => $this->order_pos));
    }

    public function delete()
    {
        Db::delete('modules', $this->id);
        unset($this);
    }

    private function set_order_position() {
        $module = Db::query('SELECT * FROM modules WHERE position LIKE :position ORDER BY order_pos DESC LIMIT 1',array('position' => $this->position), PDO::FETCH_OBJ);
        $this->order_pos = $module->order_pos+1;
    }

    public function move($dir) {
        if ($dir == 'up') {
            $module = Db::query('SELECT * FROM modules WHERE position LIKE :position AND order_pos < :order_pos ORDER BY order_pos DESC LIMIT 1',array('position' => $this->position, 'order_pos' => $this->order_pos), PDO::FETCH_OBJ);
        } else {
            $module = Db::query('SELECT * FROM modules WHERE position LIKE :position AND order_pos > :order_pos ORDER BY order_pos ASC LIMIT 1',array('position' => $this->position, 'order_pos' => $this->order_pos), PDO::FETCH_OBJ);
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
}