<?php
// Site Informations
/**--**/  $meta['title'] = _acp_site;
//------------------------------------------------

if ($do == "")
{
    switch ($action)
    {
        case 'site_add':
            $sm = new SiteManager('*');
            $te = new TemplateEngine();
            foreach ($sm->sites as $site) {
                $options[] = array("value" => $site->id, "title" => $site->title);
            } $te->addArr('options', $options);
            $te->setHtml(show("acp/acp_site_create", array('editor' => show('allround/input_editor'))));
            $disp = $te->render();
            break;
        case 'site_list':
            $sm = new SiteManager('*');
            $te = new TemplateEngine();
            foreach ($sm->sites as $site) {
                $sites[] = array('id' => $site->id, 'site_name' => $site->title);
            } $te->addArr('sites', $sites);
            $te->setHtml(show("acp/acp_site_list"));
            $disp = $te->render();
            $disp = Render::navigation_back_from(24);


            break;
        default:
            $disp = msg(_modul_not_exists);
            break;
    }
} else {
    switch ($do)
    {
        case 'create_site':
            $sm = new SiteManager(0);
            if (permTo('create_site')) {
                $sm->create_site($_POST);
                goBack();
            } else { $disp = msg(_no_permissions); }
            break;
        case 'delete_site':
            if (permTo("delete_site")) {
                if(up("DELETE FROM sites WHERE id = ".sqlInt($_POST['id']))) {
                    goBack();
                } else {
                    $disp = msg(_change_failed);
                }
            } else { $disp = msg(_no_permissions); }
            break;
    }
}