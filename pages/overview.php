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
        while ($data = _assoc($categories))
        {
            $content .= show('overview/show_all',
                    array(
                        'title' => $data['title'],
                        'id' => $data['id'],
                        'image' => $data['image']                        
                    ));
        }       
        $content = show('overview/main' ,array('content' => $content));
        break;
    case 'categorie':
        if (isset($_GET['id']))
        {
            $qry = getContentbyTags($_GET['id']);
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

function getContentbyTags($id) {
    return getContentFrom("news",getKeywordsFromCategories($id));
}

function getTopNews($id) {
    
}

function getContentFrom($site ,$keywords)
{
    return db("SELECT "
                . "n.title,"
                . "n.id,"
                . "count(c.subsite) as rate "
            . "FROM "
                . sqlString($site)." as n,"
                . "comments as c "
            . "WHERE n.keywords LIKE (".arrayTomysqlOR($keywords).") "
            . "AND c.site = 2 "
            . "AND c.subsite = n.id "
            . "AND c.date > ".(time()-36288000)." "
            . "GROUP by c.subsite");
}

function arrayTomysqlOR($arr)
{
    $tags = "";
    foreach ($arr as $value) {
        $tags .= " OR '%".$value."%'";
    }
    return substr($tags,4);
}

function getNewsFromCategory($id, $cols)
{
    getKeywordsFromCategories($id);
}

function getFromCategories($cols = "*",$id = "",$kind = null)
{
    if ($id != "") {
        $id = "WHERE id = ".sqlInt($id);
    }
    return db("SELECT ".$cols." FROM categories ".$id, $kind);
}

function getKeywordsFromCategories($id)
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

init($content, $meta);