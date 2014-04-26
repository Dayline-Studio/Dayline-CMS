<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "MyServers";
//------------------------------------------------

if ($do == "")
{
    switch ($show)
    {
        default:
            if($servers = db("SELECT * FROM server WHERE server_owner = ".$_SESSION['userid']))
            {
                while ($server = _assoc($servers))
                {   	
                    $liste .= show(
                                "servermanager/server_list_li", 
                                array(
                                    "server_name" =>  $server['server_name'],
                                    "server_ip" => $server['server_ip'],
                                    "server_kind" => ucfirst($server['server_kind']),
                                    "server_query_port" => $server['server_query_port'],
                                    "server_interface" => $server["server_interface"],
                                    "server_type" => ucfirst($server['server_type']),
                                    "id" => $server["server_id"]
                                    )
                                 );
                }
                $disp = show("servermanager/server_list", array("server" => $liste));
            } else {
                $disp = msg('Du besitzt keine Server');
            }
            break;
        case 'myserver':
            if ($server   = db("SELECT * FROM server WHERE server_id = ".sqlInt($_GET['id'])." AND server_owner = ".$_SESSION['userid'],'object'))
            {
                    $commands = db("SELECT server_aviable_commands, server_aviable_commands_text, server_aviable_input FROM server_commands WHERE server_type = '".$server->server_type."'",'object');

                $aviable_commands      = explode('/',$commands->server_aviable_commands);
                $aviable_commands_text = explode('/',$commands->server_aviable_commands_text);
                $aviable_input         = explode('/',$commands->server_aviable_input);

                foreach ($aviable_commands as $count => $command)
                {
                    $buttons .= show(
                            "servermanager/server_buttons",
                            array(
                                "aviable_commands" => $command,
                                "aviable_commands_text" => $aviable_commands_text[$count],
                                "id" => $server->server_id
                                )
                            );
                        //if ($aviable_input[$count]== 1) echo '<input type="password" size="10" maxlength="50" name="input">';
                }

                switch($server->server_type)
                {
                    case minecraft:
                    case tf2:
                    case ts3:
                        require '../content/plugins/gameq/GameQ.php';

                        $server_query = 
                            array(
                            'type' => $server->server_type,
                            'host' => $server->server_ip.':'.$server->server_port
                             );
                        $gq = new GameQ();
                        $gq->addServer($server_query);
                        $gq->setOption('timeout', 4);
                        $gq->setFilter('normalise');
                        $results = $gq->requestData();

                        foreach ($results as $data) {
                            if ($data['gq_online']) {
                                foreach($data as $col => $value) {
                                    if ($value === null) {
                                        $data[$col] = 'not aviable';
                                    }
                                    else if ($col == 'plugins') {
                                        $value = str_replace (':',';',$value); 
                                        $plugins = explode(';', $value);
                                        $data[$col] = "";
                                        foreach ($plugins as $plugin) {
                                            $data[$col] .= $plugin.'<br>';
                                        }
                                    }
                                }
                                $server_infos = show('servermanager/server_infos', $data);
                            } else {
                                $server_infos = _server_unreachable;
                            }
                        }
                        $disp = show(
                            "servermanager/server_show",
                            array(
                                'name' => $server->server_name,
                                'id' => $server->server_id,
                                'server_infos' => $server_infos,
                                'type' => $server->server_type,
                                "buttons" => $buttons
                                )
                        );
                        break;
                } 
            } else {
                $disp = msg(_no_permissions);
            }
            break;
    }
} else {
    switch ($do) 
    {
        case 'run_ssh':
            $start_time = time();
            $server_todo = $_GET['todo'];
            $server_input = $_GET['input'];

            if ($server = db("SELECT * FROM server WHERE server_owner = ".$_SESSION['userid']." AND server_id = ".  sqlInt($_GET['id']), 'object'))
            {
                $count = db("SELECT run_id FROM server_run WHERE run_user LIKE '$server->server_name'",'rows'); 

                if ($count == null)
                {
                    if ($server->server_kind == gameserver)
                    {
                        if ($server->server_type == minecraft || $server->server_type == hexxit){
                            if ($server_todo == server_interface_stop) {			
                                $command = "stop";
                            }
                            else if ($server_todo == server_interface_kill) {
                                $command = "kill";
                            }
                            else if ($server_todo == server_interface_start) {
                                $command = "start";
                            }
                            else if ($server_todo == server_interface_restart) {
                                $command = "restart";
                            } else {
                                $disp = "error 1";
                            }	
                        }
                    }
                    else if ($server->server_kind == webserver)
                    {
                        if ($server->server_type == webspace)
                        {
                            if ($server_todo == ftp_password_change)
                            {	
                                $save_for_the_root = ereg_replace("[^A-Za-z0-9?!@]", "", $server_input);
                                if (strlen($server_input) == strlen($save_for_the_root))
                                {
                                    if ($server_input != null) 
                                        $command = "pwchange/".$server_input;
                                }
                                else $disp = "Im Passwort sind nur folgende Chars erlaubt: 1-9 a-z A-Z ! ? @";
                            }
                        }		
                    }
                    else if ($server->server_kind == teamfortress2)
                    {
                        if ($server_todo == server_stop) {			
                            $command = "stop";
                        }
                        else if ($server_todo == server_kill) {
                            $command = "kill";
                        }
                        else if ($server_todo == server_start) {
                            $command = "start";
                        }
                        else if ($server_todo == server_restart) {
                            $command = "restart";
                        }
                        else if ($server_todo == server_restart) {
                            $command = "update";
                        } else {
                            $disp = "error 2";
                        }	
                    }
                    if ($command != null) 
                    {
                        if (up("INSERT INTO server_run (run_command, run_id, run_host, run_user, run_kind) VALUES ('$command', NULL, '$server->server_ip', '$server->server_name', '$server->server_type')"))
                        {
                            $disp = msg(_commend_send_successful);
                        }
                        else {
                            $disp = "Fehlgeschlagen";
                        }
                    } else {
                        $disp = "error 1";
                    }			
                }
                else $disp = msg(_command_already_in_progress);
            } else {
                $disp = msg(_no_permissions);
            }
            break;
        default:
            $disp = "failed";
    }
}

//Seite Rendern
Disp::$content = $disp;
Disp::addMeta($meta);
Disp::render();