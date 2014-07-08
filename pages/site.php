<?php
// Include CMS System
include "../inc/base.php";
//------------------------------------------------
// Site Informations
$meta['title'] = "Seite";
$meta['page_id'] = 3;
//------------------------------------------------

$sm = new SiteManager($show);
$site = $sm->get_first_site();
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

    $arr = get_available_modules_list();
    $options = '';
    foreach ($arr as $value) {
        $options .= '<option value="' . $value->class . '">' . "$value->title</option>";
    }
    $p['available_modules'] = $options;
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
$c['modules'] = $site->modules_render();
$c['site_id'] = $show;
$c['position'] = 'site-' . $site->id;
$te->add_vars($c);
$te->setHtml(show('site/main', $p));
$disp = $te->render();

$_SESSION['print_content'] = $c['modules'];
$_SESSION['print_title'] = $site->title;

$meta['title'] = $site->title;
$meta['author'] = $user->name;
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

Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();

function get_available_modules_list()
{
    $path = "../inc/modules/";
    $handle = opendir($path);
    $list = array();
    while ($file = readdir($handle)) {
        if (substr($file, -4) == '.xml') {
            $xml = simplexml_load_file($path . $file);
            $list[] = $xml;
        }

    }
    closedir($handle);
    return $list;
}