<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Overview";
//------------------------------------------------

$content = "";
switch ($show)
{
    default:
        $categories = getFromCategories();
        while ($data = _assoc($categories)) {
            $case['categories'] .= show('overview/show_all',
                    array(
                        'title' => $data['title'],
                        'id' => $data['id'],
                        'image' => $data['image']                        
                    ));
        }
        $case['news'] = getNewsBox(getQrybyTagsFromNews());
        $case['topnews'] = getNewsBox(getQrybyTagsFromTopNews());
        $content .= show('overview/main' , $case);
    
        
        break;
    case 'categorie':
        if (isset($_GET['id']))
        {
            $qry = getQrybyTagsFromNews($_GET['id']);
            while ($data = _assoc($qry))
            {
                $content .= $data['rate'].' '.$data['title'].'<br>';
            }

        } else {
            $content = msg(_categorie_not_found);
        }
        break;    
}
// Functions

function getNewsBox($qry)
{
    while ($post = _assoc($qry))
    {
        $length = 25;
        if (strlen($post['title']) > $length); {
            $post['title'] = substr($post['title'],0,$length)." ...";
        }
        $save .= show('panels/news_posts',
                            array(
                                'headline' => "(".ratePost($post['rate']).") ".$post['title'],
                                'date' => date("d\.m\. H\:i",$post['date']),
                                'post' => substr($post['description'],0,70).' ...',
                                'id' => $post['id']
                            ));
    }
    return $save;
}

//Return Query von News Order by Date
function getQrybyTagsFromNews($id) {
    return getContentFrom("news",'*','date DESC');
}

//Return Query von News Order by Rate
function getQrybyTagsFromTopNews($id) {
    return getContentFrom("news",'*','rate DESC');
}

//Return Query von Sites Order by Title
function getQrybyTagsFromSites($id) {
    return getContentFrom("sites",getKeywordsFromCategories($id),'title DESC');
}

function getTopNews($id) {
    return getContentbyTagsFromNews($id);
}

//Return Qry with Content & RATE
function getContentFrom($site ,$keywords, $orderby)
{
    if ($keywords != '*') {
        $keyString = "AND n.keywords LIKE (".arrayTomysqlOR($keywords).") ";
    } else {
        $keyString = " ";
    }
    return db("SELECT "
                . "n.title as title,"
                . "n.content as content,"
                . "c.active as active,"
                . "n.description as description,"
                . "n.id as id,"
                . "n.date as date,"
                . "count(c.subsite) as rate "
            . "FROM "
                . $site." as n,"
                . "comments as c "
            . "WHERE c.site = 2 "
            . $keyString
            . "AND c.subsite = n.id "
            . "GROUP by n.id "
            . "ORDER BY ".$orderby);
}

function ratePost($count)
{
    return ($count-1)*13;
}

//Return Array der Tags der jeweiligen Kategorie ID
function getKeywordsFromCategories($id = "")
{
    $categorie = getFromCategories("tags",$id,'object');

    foreach (tagConverter($categorie->tags) as $tag)
    {
        if ($tag != "" && $tag != " ") {
            $keywords .= $tag."|";
        }
    }
    return explode("|",substr($keywords,0,-1)); 
}

//Return Query mit Content von Kategorien
function getFromCategories($cols = "*",$id = "",$kind = null)
{
    if ($id != "") {
        $id = "WHERE id = ".sqlInt($id);
    }
    return db("SELECT ".$cols." FROM categories ".$id, $kind);
}

//Return OR String für SQL-Befehl
function arrayTomysqlOR($arr)
{
    $tags = "";
    foreach ($arr as $value) {
        $tags .= " OR '%".$value."%'";
    }
    return substr($tags,4);
}

init($content, $meta);