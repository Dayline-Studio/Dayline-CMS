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
    $sm = new SiteManager($show);
    $site = $sm->get_first_site();
    if (!empty($site)) {
        $te = new TemplateEngine();
        if (permTo('site_edit')){
            $sites = new SiteManager('*');
            foreach ($sites->sites as $s) {
                $selected = $s->id == $site->subfrom ? 'selected' : '';
                $title_sites[] = array('title' => $s->title, 'value' => $s->id, 'select' => $selected);
            }
            $case['headline_select'] = $site->show_headline ? 'checked' : '';
            $case['print_select'] = $site->show_print ? 'checked' : '';
            $case['author_select'] = $site->show_author ? 'checked' : '';
            $case['lastedit_select'] = $site->show_lastedit ? 'checked' : '';
            $te->addArr('title_sites',$title_sites);
            $te->setHtml("site/content_editable");
        } else {
            $te->setHtml("site/content");
        }
        $user = new User($site->userid);
        $case['author'] = $site->show_author ? "Written by ".$user->name." - ".date("F j, Y, g:i a",$site->date) : '';
        $case['title'] = $site->show_headline ? $site->title : '';
        $case['edited'] = $site->lastedit != "" && $site->show_lastedit ?
            "Last edit by ".$site->editby." - ".date("F j, Y, g:i a",$site->lastedit) : '';
        $case['print'] = $site->show_print ? show('site/print') : '';
        $case['link'] = $site->get_site_id();
        $case['content'] = $site->content;
        $case['site_id'] = $show;

        $te->add_vars($case);
        $disp = $te->render();

        $_SESSION['print_content'] = $site->content;
        $_SESSION['print_title'] = $site->title;

        $meta['title'] = $site->title;
        $meta['author'] = $user->name;
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
                $sm = new SiteManager($show);
                $site = $sm->get_first_site();
                $site->content = mysql_real_escape_string($_POST['mce_0']);
                $site->editby = $_SESSION['name'];
                $site->lastedit = time();
                $site->clear_checkboxes();
                $site->set($_POST);
                $site->update();
                goBack();
            } else {
               $disp = msg(_change_failed);
            }
            break;
    }
}

Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();

function getLink($title)
{
    return "../pages/site.php?show=".str_replace("_"," ",$title);
}