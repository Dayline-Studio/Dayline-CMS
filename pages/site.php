<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Seite";
/**--**/  $meta['page_id'] = 3;
//------------------------------------------------

$sm = new SiteManager($show);
$site = $sm->get_first_site();
$disp = $site->modules_render();

Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();
