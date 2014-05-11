<?php

require_once 'Post.class.php';

class News {
    
    public static $post;
    
    public function init() {
        if (self::$post === NULL) {
            self::$post = array();
            $news = Db::npquery(
                    'SELECT '
                    . 'title,'
                    . 'name,'
                    . 'grp,' 
                    . 'keywords,'
                    . 'date,'
                    . 'description,'
                    . 'gplus,'
                    . 'news.id as id'
                    . ',content '
                    . 'FROM news '
                    . 'LEFT JOIN users ON news.userid = users.id '
                    . 'ORDER BY date DESC'
                    , PDO::FETCH_OBJ);

            foreach ($news as $data) {
                self::$post[$data->id] = new Post($data);
            }
        }
    }
    
    public function createPost() {
        
    }
    
    public function getNewsFromGroup($id) {
        $res = null;
        foreach (self::$post as $group) {
            if ($group->grp == $id) {
                $res[$group->id] = $group;
            }
        }
        return $res;
    }
    
    public static function renderNewsGroup($id) {
        $news = self::getNewsFromGroup($id);
            $list_news = "";
            foreach ($news as $post)
            {
                $post->loadComments();
                $list_news .= show("news/post",array(
                    "news_headline" => $post->title,
                    "news_date" => date("F j, Y, G:i",$post->date),
                    "news_content" => $post->content,
                    "id" => $post->id,
                    "post_comment" => '<a href="../pages/news.php?id='.$post->id.'#comments">Kommentare: '.sizeof($post->comments).'</a>'
                                                ));
            }
            if ($list_news == ""){
                $list_news = _news_not_found;
            }
            return show("news/layout_posts", array("posts" => $list_news));
        }

    public static function renderNewsPost($id) {

        if (permTo('site_edit')){
            $disp = "site/content_editable";
        } else {
            $disp = "site/content"; 
        }

        $post = self::$post[$id];
        if($post != null)
        {
            //Print
            $_SESSION['print_content'] = $post->content;
            $_SESSION['print_title'] = $post->title;
            $meta['title'] = $post->title;
            $meta['keywords'] = $post->keywords;
            $meta['author'] = $post->name;
            $meta['description'] = $post->description;
            if ($post->gplus != "") {
                $meta['google_plus'] = show('allround/google_plus_head', array('google_profile' => $post->gplus));
            }
            Disp::addMeta($meta);
            return show("news/layout", array(
                                            "news_headline" => $post->title,
                                            "news_date" => date("F j, Y, G:i",$post->date),
                                            "content" => show($disp, array("content" => $post->content)),
                                            "comments" => $post->renderComments()
            ));
        } else {
            return msg(_news_post_not_found);
        }
    }
}
