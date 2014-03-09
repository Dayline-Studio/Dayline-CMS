<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "News";
/**--**/  $meta['page_id'] = 2;
//------------------------------------------------

$content = getNews(0);
init($content,$meta);