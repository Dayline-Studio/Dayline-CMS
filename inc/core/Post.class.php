<?php

class Post {

    public $comments;
    
    public function __construct($data) {
        foreach ($data as $var => $value) {
            $this->$var = $value; 
        }
		$this->date_out = convertDateOutput($this->date);
    }
    
    public function update() {
        
    }
    
    function deletePost() {
        DB::nrquery('DELETE FROM news WHERE id = '.$this->id);
        unset($this);
    }
    
    public function loadComments() {
        if ($this->comments === NULL) {
            $comments = DB::npquery('SELECT c.id id, userid, date, content, site, subsite, active, email, name FROM comments c JOIN users u on u.id = userid WHERE site = 2 AND active = 1 AND subsite = '.$this->id, PDO::FETCH_ASSOC);
            foreach ($comments as $comment) {
                $this->comments[$comment['id']] = new Comment($comment);
            }
        }
        return $this->comments;
    }
    
    public function getComments() {
        $this->loadComments();
        foreach ($this->comments as $id => $obj) {
            foreach ($obj->infos as $name => $value) {
                $comments[$id][$name] = $value;
            }
        }
        return $comments;
    }

    public function getCommentInput() {
        return show(
                'ucp/comment_input',
                array(
                    'subsite' => $this->id,
                    'site' => 2,
                    'gravatar' => get_gravatar($_SESSION['email'], 52, false),
                    'name' => $_SESSION['name']
                ));
    }
    
}
