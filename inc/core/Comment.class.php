<?php

class Comment {
    
    private $infos = array();
    
    public function __construct($data) {
        
            $this->infos = $data;
            $this->infos['gravatar'] = get_gravatar($data['email'], 52, false);
            $this->infos['date_out'] = convertDateOutput($data['date']);
            $this->infos['content'] = substr($data['content'], 0, 200);
            $this->infos['content_expand'] = substr($data['content'], 0, -200);
    }
    
    public function getComment() {
        return $this->infos;
    }
    
    public function renderComment() {
          return show("ucp/comment", $this->infos);
    }
}
