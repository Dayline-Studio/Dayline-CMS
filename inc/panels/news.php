<?php
function news(){

    $posts = "";
    News::init();
    foreach (News::$post as $post) {
        $length = 25;
        if (strlen($post->title) > $length); {
            $post->title = substr($post->title,0,$length)." ...";
        }
        $posts .= show('panels/news_posts',
                            array(
                                'headline' => $post->title,
                                'date' => date("d\.m\. H\:i",$post->date),
                                'post' => substr($post->description,0,70).' ...',
                                'id' => $post->id
                            ));
    }
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