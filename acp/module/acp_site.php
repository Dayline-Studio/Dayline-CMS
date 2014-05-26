<?php
// Site Informations
/**--**/  $meta['title'] = _acp_site;
//------------------------------------------------
  
if ($do == "")
{
    switch ($action)
    {
        default:
            $sites = db("select title,id from sites");
            $options = "";
            while ($site = _assoc($sites))
            {
                    $options .= show("site/option", array("id" => $site['id'], "title" => $site['title']));
            }   
            $disp = show("acp/acp_site_create", array("options_addafter" => $options));		
            break;
        case 'site_list':
            $sites = db('SELECT title,id FROM sites');
            $options = "";
            while ($site = _assoc($sites)) {
                $options .= show('acp/acp_select_option',
                        array(
                            'value' => $site['id'],
                            'title' => $site['title']
                        ));
            }
            $disp = show("acp/acp_site_list", array('options' => $options));
            break;
    }
} else {
    switch ($do)
    {
        case 'create_site':
            if (permTo('create_site')) {
                if(up("INSERT INTO sites (`id`, `title`, `content`, `userid`, `keywords`, `description`, `subfrom`, `position`, lastedit, editby,date)
                       VALUES (NULL, ".sqlString($_POST['title']).", '"._site_content_input."', ".sqlString($_SESSION['userid']).", ".sqlString($_POST['keywords']).", ".sqlString($_POST['description']).", ".sqlInt($_POST['subfrom']).", 0, '', '', '".time()."')")){
                $disp .= msg(_site_created_successful);
                }
            } else { $disp = msg(_no_permissions); }
            break;
        case 'delete_site':
            if (permTo("delete_site")) {
                if(up("DELETE FROM sites WHERE id = ".sqlInt($_POST['id']))) {
                    $disp = msg(_change_sucessful);
                } else {
                    $disp = msg(_change_failed);
                }
            } else { $disp = msg(_no_permissions); }
            break;
        default:
            $disp = msg(_modul_not_exists);
            break;
    }
}
 