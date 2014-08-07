<?php
// Include CMS System
/**--**/
include "../dc-inc/base.php";
//------------------------------------------------
// Site Informations
$meta['title'] = "News";
$meta['page_id'] = 2;
//------------------------------------------------

News::init();

$te = new TemplateEngine();
if (!isset($_GET['id'])) {
    $te->setHtml(show('news/post'));
    $te->addArr('posts', News::get_news_from_group(2));
    $te->render();
    Disp::$content = $te->getHtml();
} else {
    $te->setHtml(show('news/layout'));
    Disp::$content = show($te->render(), News::$post[$_GET['id']]);
}

Disp::addMeta($meta);
Disp::render();