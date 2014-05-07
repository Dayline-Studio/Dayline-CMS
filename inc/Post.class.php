<?php

class Post {

    public function __construct($data) {
        foreach ($data as $var => $value) {
            $this->$var = $value; 
        }
    }
    
    public function update() {
        
    }
    
    function deletePost() {
        DB::nrquery('DELETE FROM news WHERE id = '.$this->id);
        unset($this);
    }
}
