<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Search";
//------------------------------------------------

$disp = show("search/head");
if (isset($_GET['tags'])) {
    $disp .= search($_GET['tags']);
}

function search($tags)
{
    $search_results = '';
    $tags = exTags($tags);
    foreach ($tags as $tag)
    {
        $search_results .= replaceTextBackground(searchEngineNews($tag),$tags);
        $search_results .= replaceTextBackground(searchEngineSites($tag),$tags);
    }
    if ($search_results == null) $search_results = "Nichts gefunden ...";
    return $search_results;
}

function searchEngineNews($tag)
{
    $res = "";
    $search = db("SELECT content,title FROM news WHERE content LIKE ".sqlString("%$tag%"));
    while ($result = _assoc($search))
    {
        $res = strip_tags($result['content']);
    }
    return $res;
}

function searchEngineSites($tag)
{
    $sm = new SiteManager('*');
    if ($site = $sm->get_site_by_search(array('title','content'),$tag,0)) {
        return $site->content;
    }
    return '';
}

function exTags($tags)
{
    return explode("+", $tags);
}

function replaceTextBackground($replace_string, $tags)
{
    foreach ($tags as $tag)
    {
        $replace_string = str_ireplace($tag, '<span style="background-color: #ffff99;">'.strtoupper($tag).'</span>', $replace_string);
    }
    return $replace_string;
}

//Seite Rendern
Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();