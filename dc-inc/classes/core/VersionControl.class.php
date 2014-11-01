<?php

class VersionControl
{

    public $version, $date, $build;

    public function __construct()
    {
        $load = simplexml_load_file('../dc-version.xml');
        foreach ($load as $key => $value) {
            $this->$key = (String)$value;
        }
    }

    public function get_vars() {
        return get_object_vars($this);
    }
} 