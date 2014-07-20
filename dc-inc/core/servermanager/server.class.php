<?php

abstract class Server
{

    protected $functions = array();
    protected $htmlFile;

    public function __construct($data)
    {
        $this->port = $data['server_port'];
        $this->ip = $this->getIpFromDomain($data['server_ip']);
        $this->domain_name = $data['server_ip'];
        $this->type = $data['server_type'];
        $this->kind = $data['server_kind'];
        $this->owner = $data['server_owner'];
        $this->server_id = $data['server_id'];
        $this->name = $data['server_name'];
        $this->interface_port = $data['server_interface'];
        $this->query_port = $data['server_query_port'];
    }

    private function getIpFromDomain($str)
    {
        $ip = gethostbynamel($str);
        if (is_array($ip)) {
            return $ip[0];
        } else {
            return false;
        }
    }

    public function getServerInformations()
    {
        return get_object_vars($this);
    }

    public abstract function load_informations();

    public function getFunctions()
    {
        return $this->functions;
    }

    public function robotAddCall($call)
    {
        $case['call'] = $call;
        $case['ip'] = $this->ip;
        $case['sname'] = $this->name;
        $case['kind'] = $this->type;
        $case['email'] = $_SESSION['email'];
        return Db::nrquery(
            'INSERT INTO server_run (run_command, run_host, run_user, run_kind, email) VALUES (:call, :ip, :sname, :kind, :email)',
            $case
        );
    }

    public function handle($action)
    {
        return $this->$action();
    }

    public function getHtml()
    {
        return show($this->htmlFile);
    }

    public function dispModul1()
    {
        $disp = "";
        foreach ($this->functions as $function) {
            $case['server_id'] = $this->server_id;
            $case['action'] = $function;
            $case['value'] = con_to_lang("server_" . $function);
            $disp .= show('site/modules/servermanager/module/button', $case);
        }
        return $disp;
    }
} 