<?php

class Source extends Server
{

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
        $this->htmlFile = 'site/modules/servermanager/show_csgo';
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
        } else {
            $this->hostname = "?";
            $this->gametype = "?";
            $this->mapname = "?";
            $this->maxplayers = "?";
            $this->onlineplayers = "?";
        }
    }
}
