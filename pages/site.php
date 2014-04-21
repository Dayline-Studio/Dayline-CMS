<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Seite";
/**--**/  $meta['page_id'] = 3;
//------------------------------------------------

if ($do == "")
{
    if (permTo('site_edit')){
        $file = "site/content_editable";
       // $set_cache = false;
    }
    else{
        $file = "site/content"; 
       // $keyword_webpage = md5($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'].$_SERVER['QUERY_STRING']);
       // $content = __c("files")->get($keyword_webpage);
       // $set_cache = true;
    }
   
   // if ($content == null)
   // {
        //show to id
        $case['show'] = str_replace("_"," ",$show);

        if ($get_site = db("select * from sites where title Like ".sqlString($case['show']),'object'))
        {
                $user = getUserInformations($get_site->userid, "name");
                if ($get_site->show_author) {
                    $case['author'] = "Written by ".$user->name." - ".date("F j, Y, g:i a",$get_site->date);
                } else {
                    $case['author'] = "";
                }
                if ($get_site->show_headline) {
                    $case['title'] = $get_site->title;
                } else {
                    $case['title'] = "";
                }
                if ($get_site->lastedit != "" && $get_site->show_lastedit) {
                    $case['edited'] = "Last edit by ".$get_site->editby." - ".date("F j, Y, g:i a",$get_site->lastedit);
                } else {
                    $case['edited'] = "";
                }
                if ($get_site->show_print) {
                    $case['print'] = show('site/print');
                } else {
                    $case['print'] = "";
                }
                $case['content'] = $get_site->content;
                $case['site_id'] = $show;
                $content = show(show("site/head").show($file),$case);

                //Print
                $_SESSION['print_content'] = $get_site->content;
                $_SESSION['print_title'] = $get_site->title;

                //Loading Meta            
                $meta['title'] = $get_site->title;
                $meta['author'] = $user->name;
                $meta['keywords'] =	$get_site->keywords;
                $meta['description'] = $get_site->description;
        }
        else {
            $content = msg(_site_not_found);
        }
        //if ($set_cache) __c("files")->set($keyword_webpage,$content, 30);
    //}
}
else {
    switch ($do)
    {
        case  'update':
            if (permTo('site_edit')){
                if (up("update sites Set content = '".mysql_real_escape_string($_POST['mce_0'])."', editby = ".sqlString($_SESSION['name']).", lastedit = ".time()." where title LIKE ".sqlString($show))){
                        $content = msg(_change_sucessful);
                } else {
                    $content = msg(_change_failed);
                }
            }
            else {
               $content = msg(_change_failed);
            }
            break;
    }
}
init($content,$meta);

function getLink($title)
{
    return "../pages/site.php?show=".str_replace("_"," ",$title);
}