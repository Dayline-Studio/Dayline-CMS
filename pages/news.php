<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "News";
/**--**/  $meta['page_id'] = 2;
//------------------------------------------------

News::init();
$te = new TemplateEngine();
$te->setHtml(show('news/post'));

if (!isset($_GET['id']))
{
    $te->addArr('posts',News::getNewsFromGroup(2));
    $te->render();
}  else {
    $te->addArr('posts',News::getPostFromNews($_GET['id']));
    $te->render();
}

$te->render();
Disp::$content = $te->getHtml();
Disp::render();
//Disp::render();