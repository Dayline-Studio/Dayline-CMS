<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "PageSwitch";
//------------------------------------------------
$_SESSION['pageswich'] = $_POST['page'];
$content = msg("Done");
init($content,$meta);
