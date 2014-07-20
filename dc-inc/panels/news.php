<?php
function news(){
    News::init();
    foreach (News::$post as $post) {
        if ($post->public_show == 1 && $post->grp == 2) {
            $length = 30;
            if (strlen($post->title) > $length); {
                $post->title = substr($post->title,0,$length);
            }
            $news[] = array(
                    'headline' => $post->title,
                    'date' => date("d.m.-H:i",$post->date),
                    'post' => substr($post->description,0,70),
                    'id' => $post->id
                );
        }
    }
    $te = new TemplateEngine('panels/news_posts');
    $te->addArr('news', $news);
    $posts = $te->render();
    if ($posts != "") {
        return show("panels/box",
            array(
                'title' => "Last News",
                'name' => "news_panel",
                'content' => $posts,
            ));
    } else {
        return $posts;
    }
}