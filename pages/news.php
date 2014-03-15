<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "News";
/**--**/  $meta['page_id'] = 2;
//------------------------------------------------

if ($_GET['id']== '')
{ 
    $content = getNews(0);    
}  else {
    $newsid = $_GET['id'];
    
    $post = db("SELECT * FROM news WHERE id = ".sqlInt($newsid),'object');
    $content = show("news/post",
                    array(
                        "news_headline"	=> $post->title,
                        "news_date" => date("m.d.y",$post->date), 
                        "news_content" => $post->post
                    ));
    $content .= getCommentBox($meta['page_id'], $post->id);
}
//Initialisierung de Seite */
init($content,$meta);   