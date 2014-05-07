<?php

class Post {

    public $comments;
    
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
    
    public function loadComments() {
        if ($this->comments === NULL) {
            $comments = DB::npquery('SELECT c.id id, userid, date, content, site, subsite, active, email, name FROM comments c JOIN users u on u.id = userid WHERE site = 2 AND active = 1 AND subsite = '.$this->id);
            foreach ($comments as $comment) {
                $this->comments[$comment['id']] = new Comment($comment);
            }
        }
    }
    
    public function getComments() {
        $this->loadComments();
        return $this->comments;
    }
    
    public function renderComments() {
        $this->loadComments();
        $render = "";
        foreach ($this->comments as $comment) {
            $render .= $comment->renderComment();
        }
        return $render.show(
                'ucp/comment_input',
                array(
                    'subsite' => $this->id,
                    'site' => 2,
                    'gravatar' => get_gravatar($_SESSION['email'], 52, false),
                    'name' => $_SESSION['name']
                ));
    }
    
}
