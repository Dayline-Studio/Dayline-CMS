<?php
function site_navi()
{
    global $meta;
    if ($meta['page_id'] == 3)
    {
       global $show;
       $site = db("SELECT id FROM sites where title LIKE ".sqlString($show), 'object');
       return show("panels/box", array("content" => baumOut($site->id)));
    }
 }

function siteNaviBackwardList($id, $baum)
{
    $site = db("SELECT subfrom,id, title from sites where id = ".$id." LIMIT 1",'object');    
    $oversite = "";
    $qry = db("SELECT subfrom, title from sites where subfrom =". $site->subfrom);
    while ($get = mysqli_fetch_assoc($qry))
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
    $baum[1] = siteNaviForward($id);
    $baum[0] = 1;
    foreach ( siteNaviBackwardList($id, $baum) as $items) 
    {
        if (!is_numeric($items)) 
        {
            $ret = '<ul>'.$items.$ret.'</ul>';
        }
    }
    
    return $ret;
}

function siteNaviForward($id)
{
    $out = "";
    $sites = db("select title, subfrom, id from sites where subfrom = ".$id);
    while ($get = mysqli_fetch_assoc($sites))
    {
        $out .= show("site/site_li", array("title" => $get['title'], "link" => str_replace(" ","_",$get['title'])));
    }
    return $out;
}