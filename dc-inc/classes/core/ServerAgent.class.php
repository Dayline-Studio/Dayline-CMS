<?php

class ServerAgent
{

    public $info_php, $info_sql, $info_cms;

    public function __construct($php = true, $sql = true, $cms = true)
    {
        if($php) $this->read_server_configuration();
        if($sql) $this->read_sql_configuration();
        if($cms) $this->read_cms_configuration();
    }

    private function read_server_configuration()
    {
        function filter($str) {
            if (substr($str,-1,1) == ' ') {
                $str = substr($str,0,-1);
            }
            return strtolower($str);
        }
        ob_start();
        phpinfo();
        $info_arr = array();
        $info_lines = explode("\n", strip_tags(ob_get_clean(), "<tr><td><h2>"));
        foreach($info_lines as $line)
        {
            preg_match("~<h2>(.*)</h2>~", $line, $title) ? $cat = $title[1] : null;
            if(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val))
            {
                $info_arr[filter($val[1])] = $val[2];
            }
            elseif(preg_match("~<tr><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td><td[^>]+>([^<]*)</td></tr>~", $line, $val))
            {
                $info_arr[filter($val[1])] = array("local" => $val[2], "master" => $val[3]);
            }
        }
        $this->info_php = $info_arr;
    }

    private function read_sql_configuration() {
        $sql['server_version'] = Db::get_handler()->getAttribute(PDO::ATTR_SERVER_VERSION);
        $sql['server_info'] = Db::get_handler()->getAttribute(PDO::ATTR_SERVER_INFO);
        $sql['client_version'] = Db::get_handler()->getAttribute(PDO::ATTR_CLIENT_VERSION);
        $this->info_sql = $sql;
    }

    public function get_important_infos() {
        $p = $this->info_php;
        $n['system'] = $p['system'];
        $n['apache version'] = $p['apache version'];
        $n['php version'] = $p['php version'];
        $n['server_addr'] = $p['server_addr'];
        $n['server_port'] = $p['server_port'];
        $n['gd version'] = $p['gd version'];
        $n['http_accept_encoding'] = $p['_server["http_accept_encoding"]'];
        $n['memory_limit'] = $p['memory_limit']['master'];
        $n['upload_max_filesize'] = $p['upload_max_filesize']['master'];

        $s = $this->info_sql;
        $n['server_version'] = $s['server_version'];

        $c = $this->info_cms;
        $n['style'] = $c['style'];
        $n['force_domain'] = $c['force_domain'];
        $n['domain'] = $c['domain'];
        $n['force_https'] = $c['force_https'];
        $n['version'] = $c['version'];

        return $n;
    }

    private function read_cms_configuration() {
        $this->info_cms = (array) Config::$settings;
    }
}