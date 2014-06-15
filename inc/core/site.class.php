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

    public function get_site_id(){
        return $this->id.'-'.str_replace(array(' ', '/', '.', '+'),'-', $this->title);
    }

    public function delete(){
        return Db::delete('sites', $this->id);
    }
}