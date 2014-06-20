<?php

class ModulManager {

    public function __construct() {

    }

    public function get_available_modules_list() {
        $handle = opendir ("../inc/modules/");
        $list = array();
        while ($file = readdir ($handle)) {
            if (substr($file, -4) == '.xml'){
                $xml = simplexml_load_file($file);
                $list[] = $xml->title;
            }

        }
        closedir($handle);
        return $list;
    }

}