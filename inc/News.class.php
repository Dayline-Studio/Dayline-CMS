<?php

require_once 'Post.class.php';

class News {
    
    public static $post;
    
    public function init() {
        if (self::$post === NULL) {
            $news = Db::npquery(
                    'SELECT '
                    . 'title,'
                    . 'name,'
                    . 'keywords,'
                    . 'date,'
                    . 'description,'
                    . 'gplus,'
                    . 'news.id as id'
                    . ',content'
                    . ' FROM news '
                    . 'LEFT JOIN users ON news.userid = users.id'
                    , PDO::FETCH_OBJ);
            
            foreach ($news as $data) {
                self::$post[$data->id] = new Post($data);
            }
        }
    }
    
    public function createPost() {
        
    }
}
