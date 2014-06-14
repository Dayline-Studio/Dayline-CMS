<?php

class Servermanager {

    public $server = array();

    public function __construct() {
        $server = Db::npquery('SELECT * FROM server WHERE server_owner = '.$_SESSION['userid']);
        foreach ($server as $machine) {
            $this->server[$machine['server_id']] = new $machine['server_type']($machine);
        }
    }

    public function getServerInformations() {
        foreach ($this->server as $id => $obj) {
            $infos[$id]= $obj->getServerInformations();
        }
        return $infos;
    }
}