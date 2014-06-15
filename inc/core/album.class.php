<?php

class Album {

    public $id = NULL, $title, $path, $subfrom, $position, $main_path;
    public $images = array();

    public function __construct($data, $main_path) {
        foreach($data as $var => $value) {
            $this->$var = $value;
        }
        $this->main_path = $main_path;
        $this->scan_dir();
    }

    private function scan_dir() {
        $path = $this->main_path.$this->path.'/';
        if ($handle = opendir($path)) {
            $i = 0;
            while (false !== ($file = readdir($handle))) {
                if (!is_dir($path.$file)) {
                    $this->images[$i]['path'] = $path.$file;
                    $this->images[$i]['title'] = basename($file);
                    $i++;
                }
            }
            closedir($handle);
        }
    }
}