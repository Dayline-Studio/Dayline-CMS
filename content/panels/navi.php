<?php
function navi()
{
    global $show, $meta;
    $title = "Navigation";
    switch(substr(basename($_SERVER["PHP_SELF"]),0,-4))
    {
        case 'site':
            $site = db("SELECT id from sites where title like ".sqlString(str_replace("_"," ",$show)),'object');
            $content =  siteNaviBackward($site->id);
            break;
        default:
            $content = '<a href="../pages/'.basename($_SERVER["PHP_SELF"]).'">'.ucfirst(substr(basename($_SERVER["PHP_SELF"]),0,-4))."</a>";
            break;
        case 'categories':
            $content = '<a href="../pages/'.basename($_SERVER["PHP_SELF"]).'">'.ucfirst(substr(basename($_SERVER["PHP_SELF"]),0,-4))."</a>";
            break;
   }
   
   if (isset($meta['page_id']) && $meta['page_id'] == 3){
       $site_navi = baumOut($site->id);
   } else {
       $site_navi = "";
   }
   
   return show("panels/box", array("content" => $content.$site_navi, "title" => $title, "name" => __FUNCTION__));
}

function siteNaviBackward($id)
{
    $site = db("SELECT subfrom, title from sites where id = ".$id." LIMIT 1",'object');    
    $oversite = "";
    if ($site->subfrom != 0)
    {
        $oversite = siteNaviBackward($site->subfrom).' >> ';
    }
    return $oversite.'<a href="../pages/site.php?show='.str_replace(" ","_",$site->title).'">'.$site->title.'</a>';
}

function siteNaviBackwardList($id, $baum)
{
    $site = db("SELECT subfrom,id, title from sites where id = ".$id." LIMIT 1",'object');    
    $oversite = "";
    $qry = db("SELECT subfrom, title from sites where subfrom =". $site->subfrom);
    while ($get = _assoc($qry))
    {
        $subfrom = $get['subfrom'];
        $oversite .= show("site/site_li", array("title" => $get['title'], "link" => str_replace(" ","_",$get['title'])));
    }  
    
    $baum[0]++;
    $baum[$baum[0]] = $oversite;
    
    if ($subfrom != 0) {
        $baum = siteNaviBackwardList($site->subfrom, $baum);
    }
    
    return $baum;
}

function baumOut($id)
{
    $ret = "";
    $baum[1] = siteNaviForward($id);
    $baum[0] = 1;
    
    foreach ( siteNaviBackwardList($id, $baum) as $items) {
        if (!is_numeric($items)) {
            $ret = '<ul>'.$items.$ret.'</ul>';
        }
    }
    
    return $ret;
}

function siteNaviForward($id)
{
    $out = "";
    $sites = db("select title, subfrom, id from sites where subfrom = ".$id);
    while ($get = _assoc($sites))
    {
        $out .= show("site/site_li", array("title" => $get['title'], "link" => str_replace(" ","_",$get['title'])));
    }
    return $out;
}