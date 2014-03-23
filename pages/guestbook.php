<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Gästebuch";
/**--**/  $meta['page_id'] = 5;
//------------------------------------------------
init(dispComments($meta['page_id'],0),$meta);