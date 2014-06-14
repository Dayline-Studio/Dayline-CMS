<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Seite";
/**--**/  $meta['page_id'] = 3;
//------------------------------------------------

$disp = '';
if ($do == "")
{
    if (permTo('site_edit')){
        $file = "site/content_editable";
    } else {
        $file = "site/content";
    }
    $case['show'] = str_replace("_"," ",$show);

    $sm = new SiteManager(array($show));
    $site = $sm->get_first_site();
    if (!empty($site)) {
        $user = getUserInformations($site->userid, "name");
        if ($site->show_author) {
            $case['author'] = "Written by ".$user->name." - ".date("F j, Y, g:i a",$site->date);
        } else {
            $case['author'] = "";
        }
        if ($site->show_headline) {
            $case['title'] = $site->title;
        } else {
            $case['title'] = "";
        }
        if ($site->lastedit != "" && $site->show_lastedit) {
            $case['edited'] = "Last edit by ".$site->editby." - ".date("F j, Y, g:i a",$site->lastedit);
        } else {
            $case['edited'] = "";
        }
        if ($site->show_print) {
            $case['print'] = show('site/print');
        } else {
            $case['print'] = "";
        }
        $case['content'] = $site->content;
        $case['site_id'] = $show;
        $disp = show(show("site/head").show($file),$case);

        //Print
        $_SESSION['print_content'] = $site->content;
        $_SESSION['print_title'] = $site->title;

        //Loading Meta
        $meta['title'] = $site->title;
        //$meta['author'] = $user->name;
        $meta['keywords'] =	$site->keywords;
        $meta['description'] = $site->description;
    } else {
        $disp = msg(_site_not_found);
    }
}
else {
    switch ($do)
    {
        case  'update':
            if (permTo('site_edit')){
                    $sm = new SiteManager(array($show));
                    $site = $sm->get_first_site();
                    $site->content = mysql_real_escape_string($_POST['mce_0']);
                    $site->editby = $_SESSION['name'];
                    $site->lastedit = time();
                    $site->title = $show;
                    $site->update();
                    goBack();
            } else {
               $disp = msg(_change_failed);
            }
            break;
    }
}

//Seite Rendern
Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();

function getLink($title)
{
    return "../pages/site.php?show=".str_replace("_"," ",$title);
}