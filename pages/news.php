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
    $content = getNews(2);    
}  else {
    
    if (permTo('site_edit')){
    $content = "site/content_editable";
    }
    else{
        $content = "site/content"; 
    }
    
    $newsid = $_GET['id'];
    
    $post = db("SELECT * FROM news WHERE id = ".sqlInt($newsid),'object');
    
    $content = show($content, array("content" => $post->post)).dispComments($meta['page_id'], $post->id);
}

//Initialisierung der Seite */
init($content,$meta);   