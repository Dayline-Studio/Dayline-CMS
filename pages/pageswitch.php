<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "PageSwitch";
//------------------------------------------------
$_SESSION['pageswich'] = $_POST['page'];
$content = msg("Done");
init($content,$meta);
