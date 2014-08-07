<?php
// Include CMS System
include "../dc-inc/base.php";
//------------------------------------------------
// Site Informations
$meta['title'] = "Seite";
$meta['page_id'] = 3;
//------------------------------------------------
$sm = new SiteManager($show);
if($site = $sm->get_first_site()) {
    $site_id  = $site->get_site_id();

    if  (!stristr(urldecode($_SERVER['REQUEST_URI']),$site->get_url())) {
        header("Location: ".$site->get_url());
        exit();
    }

    $p = array();
    $te = new TemplateEngine();
    if (permTo('site_edit') && !$_SESSION['prev_mode']) {
        $sites = new SiteManager('*');
        foreach ($sites->sites as $s) {
            $selected = $s->id == $site->subfrom ? 'selected' : '';
            $title_sites[] = array('title' => $s->title, 'value' => $s->id, 'select' => $selected);
        }
        $c['headline_select'] = $site->show_headline ? 'checked' : '';
        $c['print_select'] = $site->show_print ? 'checked' : '';
        $c['author_select'] = $site->show_author ? 'checked' : '';
        $c['lastedit_select'] = $site->show_lastedit ? 'checked' : '';
        $c['title_admin'] = $site->title;
        $te->addArr('title_sites', $title_sites);
        $p['admin'] = show('site/admin');
    } else {
        $p['admin'] = '';
    }
    $user = new User($site->userid);
    $c['author'] = $site->show_author ? "Written by " . $user->name . " - " . date("F j, Y, g:i a", $site->date) : '';
    $c['title'] = $site->show_headline ? $site->title : '';
    $c['edited'] = $site->lastedit != "" && $site->show_lastedit ?
        "Last edit by " . $site->editby . " - " . date("F j, Y, g:i a", $site->lastedit) : '';
    $c['print'] = $site->show_print ? show('site/print') : '';
    $c['link'] = $site->get_site_id();
    $c['modules'] = '{position_site-'.$site->id.'}';
    $c['position'] = 'site-' . $site->id;
    $te->add_vars($c);
    $te->setHtml(show('site/main', $p));
    $disp = $te->render();

    $_SESSION['print_content'] = Disp::replace_paths(Disp::read_modules($te->getHtml()));

    $meta['title'] = $site->title;
    $meta['author'] = $user->firstname . ' ' . $user->lastname;
    $meta['keywords'] = $site->keywords;
    $meta['description'] = $site->description;

    switch ($do) {
        case  'update':
            if (permTo('site_edit')) {
                $sm = new SiteManager($show);
                $site = $sm->get_first_site();
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
        case 'swap_prev_mode':
            $_SESSION['prev_mode'] = $_SESSION['prev_mode'] ? 0 : 1;
            goToWithMsg('back', 'Prev Mode Changed');
            break;
    }
} else {
    goToSite('/error?r=sitenotfound');
}


Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();