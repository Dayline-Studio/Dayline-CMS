<?php
// Include CMS System
/**--**/
include "../dc-inc/base.php";
//------------------------------------------------
// Site Informations
$meta['title'] = "Error";
$meta['page_id'] = 13;
//------------------------------------------------

switch($_REQUEST['r']) {
    case 'sitenotfound':
        $disp = show('allround/sitenotfound');
        break;
}
$myDisplay = new Display($meta);
$myDisplay->setContent($disp);
$myDisplay->render();