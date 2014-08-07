<?php

class Fm_Folder
{

    public $path;
    public $current_path_rel;
    public $folders;
    public $files;
    public $name;
    public $base_path;
    private static $base;
    private static $path_rel;

    public function __construct($path)
    {
        $this->path = $path;
        $this->name = basename($path);
        $this->base_path = str_replace(Fm_Folder::$base,'',$path);
        $this->search_files();
        $this->current_path_rel = $this->get_current_path_rel();
    }

    public static function set_base($base) {
        Fm_Folder::$base = $base;
    }

    public static function set_path_rel($str) {
        Fm_Folder::$path_rel = $str;
    }

    protected function search_files()
    {
        $handle = opendir($this->path);
        while ($file = readdir($handle)) {
            if ($file != '..' && $file != '.') {
                if (is_dir($this->path.$file)) {
                    $this->folders[] = new Fm_Folder($this->path.$file.'/');
                } else {
                    $this->files[] = new Fm_File($this->path.$file);
                }
            }
        }
        closedir($handle);
    }

    private function get_current_path_rel() {
        $f = explode(Fm_Folder::$path_rel,$this->path);
        return Fm_Folder::$path_rel.$f[1];
    }

}