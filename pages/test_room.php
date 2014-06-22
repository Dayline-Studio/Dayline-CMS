<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Seite";
/**--**/  $meta['page_id'] = 3;
//------------------------------------------------


Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();
