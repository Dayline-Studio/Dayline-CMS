<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "MyServers";
//------------------------------------------------

if (isset($_POST['show'])) {
    $show = $_POST['show'];
}

$te = new TemplateEngine();
$sm = new Servermanager();

switch ($show) {
    default:
        $te->setHtml(show('servermanager/layout_list'));
        $te->addArr('server',$sm->getServerInformations());
        $te->render();
        break;
    case 'server':
        $server = $sm->server[$_GET['id']];
        $server->load_status();
        $infos = $server->getServerInformations();
        $te->addArr('plugins', $infos['plugins']);
        $infos['plugins'] = NULL;
        $infos['dispModul1'] = $server->dispModul1();
        $te->setHtml(show($server->getHtml(), $infos));
        $te->render();
        break;
    case 'action':
        $server = $sm->server[$_GET['id']];
        if (isset($_GET['do'])) {
            $do = $_GET['do'];
            if ($server->handle($do)) {
                $disp = msg(_command_send_successful);
            } else {
                $disp = 'Error 4';
            }

        }
        $te->setHtml($disp);
        break;
}

Disp::addMeta($meta);
Disp::$content = $te->getHtml();
Disp::render();
