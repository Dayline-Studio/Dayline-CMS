<?php
function navi()
{
    global $show;
    $title = "Navigation";
    switch(substr(basename($_SERVER["PHP_SELF"]),0,-4))
    {
        case 'site':
                $site = db("SELECT id from sites where title like ".sqlString(str_replace("_"," ",$show)),'object');
                $content =  siteNaviBackward($site->id);
                break;
        default:
            $content = ucfirst(substr(basename($_SERVER["PHP_SELF"]),0,-4));
   }
   return show("panels/box", array("content" => $content, "title" => $title, "name" => __FUNCTION__));
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