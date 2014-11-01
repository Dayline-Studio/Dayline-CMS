<?php
class FmFile {

    public $name;

    public function __construct($file) {
        $this->name = basename($file);
    }
}