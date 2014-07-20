<?php

class ServerManagerModule extends MainModule
{
    public $tag_stats;
    private $available_tasks = array('show_single_server', 'send_robot_task'), $results = [];

    protected function render()
    {
        if (isset($_POST['module_task'])) {
            $task = $_POST['module_task'];
            if (in_array($task, $this->available_tasks)) {
                $render = $this->$task();
                unset($_POST);
                return $render;
            }
        } else {
            $te = new TemplateEngine('site/modules/servermanager/layout_list');
            $sm = new Servermanager();
            $te->addArr('server', $sm->getServerInformations());
            return $te->render();
        }
    }

    protected function render_admin()
    {
        return "";
    }

    private function show_single_server()
    {
        $sm = new Servermanager;
        $te = new TemplateEngine();
        $server = $sm->server[$_POST['server_id']];
        $server->load_informations();
        $infos = $server->getServerInformations();
        $infos['dispModul1'] = $server->dispModul1();
        $te->add_vars($infos);
        $te->setHtml($server->getHtml());
        return $te->render();
    }

    private function send_robot_task()
    {
        $sm = new Servermanager();
        $server = $sm->server[$_POST['server_id']];
        if (isset($_POST['do'])) {
            if ($server->handle($_POST['do'])) {
                $disp = '<div class="alert alert-success" role="alert">' . _command_send_successful . '</div>';
            } else {
                $disp = 'Error 4';
            }
        }
        return $disp;
    }
}