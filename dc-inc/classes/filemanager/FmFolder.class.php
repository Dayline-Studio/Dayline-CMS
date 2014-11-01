<?php

class FmFolder
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
        $this->base_path = str_replace(FmFolder::$base,'',$path);
        $this->search_files();
        $this->current_path_rel = $this->get_current_path_rel();
    }

    public static function set_base($base) {
        FmFolder::$base = $base;
    }

    public static function set_path_rel($str) {
        FmFolder::$path_rel = $str;
    }

    protected function search_files()
    {
        $handle = opendir($this->path);
        while ($file = readdir($handle)) {
            if ($file != '..' && $file != '.') {
                if (is_dir($this->path.$file)) {
                    $this->folders[] = new FmFolder($this->path.$file.'/');
                } else {
                    $this->files[] = new FmFile($this->path.$file);
                }
            }
        }
        closedir($handle);
    }

    private function get_current_path_rel() {
        $f = explode(FmFolder::$path_rel,$this->path);
        return FmFolder::$path_rel.$f[1];
    }

}