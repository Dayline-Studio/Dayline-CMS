<?php
function navi()
{
    global $show;
    switch(substr(basename($_SERVER["PHP_SELF"]),0,-4))
    {
        case 'site':
                $site = db("SELECT id from sites where title like ".sqlString(str_replace("_"," ",$show)),'object');
                return siteNaviBackward($site->id);
                break;
        default:
            return ucfirst(substr(basename($_SERVER["PHP_SELF"]),0,-4));
   }
}


function siteNaviBackward($id)
{
    $site = db("SELECT subfrom, title from sites where id = ".$id." LIMIT 1",'object');    
    $oversite = "";
    if ($site->subfrom != 0)
    {
        $oversite = siteNaviBackward($site->subfrom).' >> ';
    }
    return $oversite.'<a href="../pages/site.php?show='.str_replace(" ","_",$site->title).'">'.$site->title.'</a>';;
}