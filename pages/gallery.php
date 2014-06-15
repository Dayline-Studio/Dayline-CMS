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

Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();