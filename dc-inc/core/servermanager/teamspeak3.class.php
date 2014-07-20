<?php

class Teamspeak3 extends Server {

    private $ts3_instance;

    public function __construct($data) {
        parent::__construct($data);
        $this->functions = array(
            'kick_all'
        );
        $this->query =
            array(
                'id' => $this->server_id,
                'type' => $this->type,
                'host' => $this->ip.':'.$this->port
            );
        $this->htmlFile = 'servermanager/show_teamspeak3';
    }

    private function load_instance() {
        $this->ts3_instance = TeamSpeak3::factory("serverquery://username:password@127.0.0.1:10011/");
    }

    public function load_informations() {
        $gq = new GameQ();
        $gq->addServer($this->query);
        $gq->setOption('timeout', 4);
        $gq->setFilter('normalise');
        $status = $gq->requestData()[$this->query['id']];
        if ($status['gq_online']) {
            $this->hostname = utf8_encode($status['gq_hostname']);
            $this->maxplayers = $status['gq_maxplayers'];
            $this->onlineplayers = $status['gq_numplayers'];
        } else {
            $this->hostname = "?";
            $this->maxplayers = "?";
            $this->onlineplayers = "?";
        }
    }
}