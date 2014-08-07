<?php

class FileManager
{
    private $upload_path;
    private $current_path;
    private $path_rel;
    public $dir;
    public $current_history;
    private $static_vars = '';
    private $hierarchy;

    public function __construct($current_path = '')
    {
        $this->upload_path = Config::$path['upload'];
        $this->current_history = explode('/', $current_path);
        $this->current_path = $this->upload_path . $current_path;
        $this->path_rel = Config::$path['upload_rel'];
        if (substr($this->current_path, -1, 1) != '/') {
            $this->current_path .= '/';
        }
        $this->load_dir();

        switch (isset($_GET['modus']) ? $_GET['modus'] : '') {
            case 'get_path':
                $this->static_vars .= 'modus=get_path';
                break;
        }
        $this->set_static_vars();
    }

    private function set_static_vars()
    {
        Disp::addMeta(array('static_vars' => $this->static_vars));
    }

    public function load_dir()
    {
        Fm_Folder::set_base($this->upload_path);
        Fm_Folder::set_path_rel($this->path_rel);
        $this->dir = new Fm_Folder($this->upload_path);
    }

    public function get_parent_folder_from_current()
    {
        return $this->hierarchy[sizeof($this->hierarchy)-1];
    }

    public function get_current_folder()
    {
        $current = $this->dir;
        foreach ($this->current_history as $folder_name) {
            if (!empty($folder_name) && $folder_name != '') {
                $this->hierarchy[] = $current;
                $current = $this->search_folder($current, $folder_name);
            }
        }
        return $current;
    }

    private function search_folder($folder, $name)
    {
        foreach ($folder->folders as $fol) {
            if ($fol->name == $name) {
                return $fol;
            }
        }
        return false;
    }
}