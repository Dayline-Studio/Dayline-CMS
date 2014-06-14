<?php
class Site {

    public $id = NULL, $title, $content, $userid, $keywords, $description, $subfrom, $position, $lastedit, $editby, $date;
    public $show_lastedit, $show_author, $show_print, $show_headline;

    public function __construct($data) {
        foreach($data as $var => $value) {
            $this->$var = $value;
        }
    }

    public function update() {
        Db::update('sites',$this->id,get_object_vars($this));
    }
}