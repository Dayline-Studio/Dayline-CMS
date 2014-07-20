<?php

class Minecraft extends Server
{

    private $query;

    private $plugins = array();

    public function __construct($data)
    {
        parent::__construct($data);
        $this->functions = array(
            'start', 'stop', 'restart', 'kill'
        );

        $this->query =
            array(
                'id' => $this->server_id,
                'type' => $this->type,
                'host' => $this->ip . ':' . $this->port
            );

        $this->htmlFile = 'site/modules/servermanager/show_minecraft';
    }

    public function load_informations()
    {
        $gq = new GameQ();
        $gq->addServer($this->query);
        $gq->setOption('timeout', 4);
        $gq->setFilter('normalise');
        $status = $gq->requestData()[$this->query['id']];
        if ($status['gq_online']) {
            $this->hostname = utf8_encode($status['gq_hostname']);
            $this->gametype = $status['gq_gametype'];
            $this->mapname = $status ['gq_mapname'];
            $this->maxplayers = $status['gq_maxplayers'];
            $this->onlineplayers = $status['gq_numplayers'];

            $plugins = str_replace(':', ';', $status['plugins']);
            $plugins = explode(';', $plugins);
            foreach ($plugins as $plugin) {
                $this->plugins[]['plugin'] = $plugin;
            }
        } else {
            $this->hostname = "?";
            $this->gametype = "?";
            $this->mapname = "?";
            $this->maxplayers = "?";
            $this->onlineplayers = "?";
            $this->plugins[0]['plugin'] = '?';
        }
    }

    public function start()
    {
        return $this->robotAddCall('start');
    }

    public function stop()
    {
        return $this->robotAddCall('stop');
    }

    public function restart()
    {
        return $this->robotAddCall('restart');
    }

    public function kill()
    {
        return $this->robotAddCall('kill');
    }

    private function preRenderInfos()
    {
        $te = new TemplateEngine($this->htmlFile);
        $te->addArr('plugins', $this->plugins);
        $this->plugins = NULL;
        return $te->render();
    }

    public function getHtml()
    {
        return $this->preRenderInfos();
    }
}