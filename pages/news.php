<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "News";
//------------------------------------------------

$content = getNews(0);
init($content,$meta);