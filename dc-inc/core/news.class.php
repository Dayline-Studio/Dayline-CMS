<?php

class News
{

    public static $post;

    public static function init()
    {
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
                . 'gplus, public_show, '
                . 'news.id as id'
                . ',content '
                . 'FROM news '
                . 'LEFT JOIN users ON news.userid = users.id '
                . 'ORDER BY date DESC'
                , PDO::FETCH_OBJ);

            if (!empty($news)) {
                foreach ($news as $data) {
                    self::$post[$data->id] = new Post($data);
                }
            }
        }
    }

    public static function createPost($arr)
    {
        $arr['date'] = time();
        return Db::insert('news', $arr);
    }

    public static function get_news_from_group($id)
    {
        $res = array();
        foreach (self::$post as $group) {
            if ($group->grp == $id && $group->public_show) {
                $res[$group->id] = $group;
            }
        }
        return $res;
    }

    public static function renderNewsPost($id)
    {

        if (permTo('site_edit')) {
            $disp = "site/content_editable";
        } else {
            $disp = "site/content";
        }

        $post = self::$post[$id];
        if ($post != null) {
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
                "news_date" => date("F j, Y, G:i", $post->date),
                "content" => show($disp, array("content" => $post->content)),
                "comments" => $post->renderComments()
            ));
        } else {
            return msg(_news_post_not_found);
        }
    }
}
