<?php

class Album {

    public $id = NULL, $title, $subfrom, $position;

    public function __construct($data, $main_path) {
        foreach($data as $var => $value) {
            $this->$var = $value;
        }
        if(!empty($data->path)) {
            $this->main_path = $main_path;
            $this->is_category = FALSE;
            $this->scan_dir();
        } else {
            unset($this->path);
            $this->is_category = TRUE;
        }
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