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
    News::$post[$_GET['id']]->loadComments();
    $te->addArr('comments', News::$post[$_GET['id']]->getComments());
    $te->setHtml(show('news/layout'));
    $te->render();
    Disp::$content = show($te->getHtml() . News::$post[$_GET['id']]->getCommentInput(), News::$post[$_GET['id']]);
}

Disp::addMeta($meta);
Disp::render();