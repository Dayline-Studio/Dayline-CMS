<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "News";
/**--**/  $meta['page_id'] = 2;
//------------------------------------------------
if (!isset($_GET['id']))
{ 
    $disp = getNews(2);    
}  else {
    
    if (permTo('site_edit')){
    $disp = "site/content_editable";
    } else {
        $disp = "site/content"; 
    }

    News::init();
    $post = News::$post[$_GET['id']];
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
        $disp = show("news/layout", array(
                                        "news_headline" => $post->title,
                                        "news_date" => date("F j, Y, G:i",$post->date),
                                        "content" => show($disp, array("content" => $post->content)),
                                        "comments" => dispComments($meta['page_id'], $post->id)
        ));
    } else {
        $disp = msg(_news_post_not_found);
    }
}

//Seite Rendern
Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();