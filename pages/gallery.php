<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Gallery";
/**--**/  $meta['page_id'] = 25;
//------------------------------------------------

if (isset($_GET['id'])) {
    $gallery_id = $_GET['id'];
    $gm = new GalleryManager(array($gallery_id));
    $album = $gm->get_first_album();
    $te = new TemplateEngine();
    $te->addArr('images', $album->images);
    $te->add_var('title', $album->title);
    $te->setHtml('gallery/album');
    $meta['title'] = $album->title.' - Album';
    $disp = $te->render();
}

$disp = navigation_back_from(0);

Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();


function navigation_back_from($id) {

    $sm = new GalleryManager('*');
    print_r($sm->get_subalbum_from(0));
    $sites = $sm->get_subalbum_from($id);
    $menu = '<ul>';
    foreach ($sites as $site) {
        $menu .= '<li>'.$site->title.get_menu($site).'</li>';
    }
    $menu .= '</ul>';
    return $menu;
}

function get_menu($site) {
    $menu = '';
    if (isset($site->subsites)) {
        $menu .= '<ul>';
        foreach($site->subsites as $subsite) {
            $menu .= '<li>'.$subsite->title.get_menu($subsite).'</li>';
        }
        $menu .= '</ul>';
    }
    return $menu;
}