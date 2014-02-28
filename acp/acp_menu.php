<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Menü Konfigurieren";
//------------------------------------------------
// Site Permissions
/**--**/ if (!permTo("site_create")) { msg('no_permissions'); }
//------------------------------------------------

$menu = db("SELECT * FROM menu");
$items = '<option value="0">Keiner</option>';
while ($eintrag = mysqli_fetch_assoc($menu))
{
    $items .= '<option value="'.$eintrag['id'].'">'.$eintrag['title'].'</option>';
}

while ($eintrag = mysqli_fetch_assoc($menu))
{
    $list .= $eintrag['title'].'<br/>';
}

switch($action){
    default:
        $output = show("acp/acp_menu_item", array("options" => $items));
        break;
}



init($output);