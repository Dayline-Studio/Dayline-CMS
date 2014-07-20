<?php
// Site Informations
/**--**/
$meta['title'] = _acp_site;
//------------------------------------------------

if ($do == "") {
    switch ($action) {
        case 'site_add':
            $sm = new SiteManager('*');
            $te = new TemplateEngine();
            $options = [];
            if (isset($sm->sites)) {
                foreach ($sm->sites as $site) {
                    $options[] = array("value" => $site->id, "title" => $site->title);
                }
            }
            $te->addArr('options', $options);
            $te->setHtml("acp/acp_site_create");
            $disp = $te->render();
            break;
        default:
            $sm = new SiteManager('*');
            $te = new TemplateEngine();
            $sites = [];
            if (isset($sm->sites)) {
                foreach ($sm->sites as $site) {
                    $sites[] = array('id' => $site->id, 'title' => $site->title, 'edit_link' => '../pages/site?show=' . $site->get_site_id(), 'where' => $_GET['acp']);
                }
            }
            $te->addArr('rows', $sites);
            $te->setHtml("acp/acp_list");
            $disp = $te->render();
    }
} else {
    switch ($do) {
        case 'create_site':
            $sm = new SiteManager(0);
            if (permTo('create_site')) {
                $sm->create_site($_POST);
                header('Location: ?acp=acp_site&action=site_list');
            } else {
                $disp = msg(_no_permissions);
            }
            break;
        case 'delete':
            if (permTo("delete_site")) {
                $sm = new SiteManager($_GET['id']);
                $site = $sm->get_first_site();
                if ($site->delete()) {
                    goBack();
                } else {
                    $disp = msg(_change_failed);
                }
            } else {
                $disp = msg(_no_permissions);
            }
            break;
    }
}