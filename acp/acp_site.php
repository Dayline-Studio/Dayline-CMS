<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Seite Erstellen";
//------------------------------------------------
// Site Permissions
/**--**/ if (!permTo("site_create")) msg('no_permissions');
//------------------------------------------------
 
switch ($action)
{
    case 'site_create':
            $show = show("acp/acp_site_create");		
    break;
}

switch ($do)
{
    case 'new_site':
            if(up("INSERT INTO sites (`id`, `title`, `content`, `author`, `keywords`, `description`) 
                   VALUES (NULL, ".sqlString($_POST['title']).", '"._site_content_input."', ".sqlString($_SESSION['name']).", ".sqlString($_POST['keywords']).", ".sqlString($_POST['description']).")")){
            $show = msg("site_created_sucessful");
            }
            break;
}
 
init($show, $meta);