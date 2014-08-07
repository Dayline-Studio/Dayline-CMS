<?php

class Site
{

    public $id = NULL, $title, $userid, $keywords, $description, $subfrom, $position, $lastedit, $editby, $date;
    public $show_lastedit, $show_author, $show_print, $show_headline, $show_socialbar, $public;
    protected $modules;

    public function __construct($data)
    {
        $this->set($data);
    }

    public function get_module($id)
    {
        return $this->modules[$id];
    }

    public function modules_load()
    {
        $this->modules = array();
        $modules = Db::npquery("SELECT id, module FROM modules WHERE position LIKE 'site-" . $this->id . "' ORDER BY order_pos", PDO::FETCH_OBJ);
        foreach ($modules as $module) {
            $this->modules[$module->id] = new $module->module($module->id);
        }
    }

    public function modules_render($force_user = 0)
    {
        $this->modules_load();
        $render = '';
        foreach ($this->modules as $module) {
            $render .= $module->full_render($force_user);
        }
        return $render;
    }

    public function update()
    {
        Db::update('sites', $this->id, get_public_properties($this));
    }

    public function get_site_id()
    {
        return $this->get_title() . '-' . $this->id;
    }

    public function get_title()
    {
        return str_replace(array(' ', '/', '.', '+'), '-', $this->title);
    }

    public function delete()
    {
        return Db::delete('sites', $this->id);
    }

    public function set($arr)
    {
        foreach ($arr as $tag => $value) {
            if (property_exists($this, $tag)) {
                $this->$tag = $value;
            }
        }
    }

    public function clear_checkboxes()
    {
        $this->show_author = 0;
        $this->show_headline = 0;
        $this->show_lastedit = 0;
        $this->show_print = 0;
        $this->show_socialbar = 0;
    }

    public function get_o_site()
    {
        if ($this->subfrom) {
            $sm = new SiteManager($this->subfrom);
            return $sm->get_first_site();
        } else {
            return false;
        }
    }

    public function get_url_part($is_cat = false)
    {
        $str = '';
        if ($cat = $this->get_o_site()) {
            $str .= $cat->get_url_part(true) . '/';
        }
        $str .= !$is_cat ? $this->get_site_id() : $this->get_title();
        return $str;
    }

    public function get_url()
    {
        return Config::$path['subfolder'] . '/' . $this->get_url_part();
    }

    public function swap_visibility() {
        $this->public = $this->public ? 0 : 1;
        return Db::update('sites', $this->id, array('public' => $this->public));
    }
}