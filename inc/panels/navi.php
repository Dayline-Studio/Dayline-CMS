<?php
function navi()
{
    global $show, $meta;
    $title = "Navigation";
    switch(substr(basename($_SERVER["PHP_SELF"]),0,-4))
    {
        case 'site':
            $sm = new SiteManager(array($show));
            $content =  siteNaviBackward($sm->get_first_site()->id);
            break;
        default:
            $content = '<a href="../pages/'.basename($_SERVER["PHP_SELF"]).'">'.ucfirst(substr(basename($_SERVER["PHP_SELF"]),0,-4))."</a>";
            break;
        case 'categories':
            $content = '<a href="../pages/'.basename($_SERVER["PHP_SELF"]).'">'.ucfirst(substr(basename($_SERVER["PHP_SELF"]),0,-4))."</a>";
            break;
   }
   
   if (isset($meta['page_id']) && $meta['page_id'] == 3){
       $rn = new Render();
       $site_navi = $rn->navigation_back_from($site->id);
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