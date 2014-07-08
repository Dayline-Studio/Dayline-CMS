<?php

class Fm_Folder
{

    public $path;
    public $folders;
    public $files;
    public $name;
    public $base_path;
    private $base;

    public function __construct($path, $base)
    {
        $this->base = $base;
        $this->path = $path;
        $this->name = basename($path);
        $this->base_path = str_replace($base,'',$path);
        $this->search_files();
    }

    protected function search_files()
    {
        $handle = opendir($this->path);
        while ($file = readdir($handle)) {
            if ($file != '..' && $file != '.') {
                if (is_dir($this->path.$file)) {
                    $this->folders[] = new Fm_Folder($this->path.$file.'/', $this->base);
                } else {
                    $this->files[] = new Fm_File($this->path.$file);
                }
            }
        }
        closedir($handle);
    }

}