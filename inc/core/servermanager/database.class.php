<?php

class Database extends Server {

    public function __construct($data) {
        parent::__construct($data);
        $this->functions = array(
            'change_pw', 'backup'
        );

        $this->htmlFile = 'servermanager/show_database';
    }

    private function cache_pw() {
        
    }
    
    private function backup() {
        
    }
    
    public function load_informations() {

    }
}