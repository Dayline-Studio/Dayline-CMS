<?php
class Fm_File {

    public $name;

    public function __construct($file) {
        $this->name = basename($file);
    }
}