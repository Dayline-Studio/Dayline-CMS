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
    $tags = exTags($tags);
    foreach ($tags as $tag)
    {
        $search_results .= replaceTextBackground(searchEngine($tag),$tags);
    }
    if ($search_results == null) $search_results = "Nichts gefunden ...";
    return $search_results;
}

function searchEngine($tag)
{
    $search = db("SELECT content,title FROM news WHERE content LIKE ".sqlString("%$tag%"));
    while ($result = _assoc($search))
    {
        $res = strip_tags($result['content']);
    }
    return $res;
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

init($disp,$meta);