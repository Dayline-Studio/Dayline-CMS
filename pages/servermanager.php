<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "MyServers";
//------------------------------------------------

if ($_SESSION['userid'] != 0)
{
    switch ($show)
    {
        default:
            if($do == "")
            {
                $servers = db("SELECT * FROM server WHERE server_owner = ".$_SESSION['userid']);
                for ($i=0 ; $server = _assoc($servers) ; $i++)
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
                $content = show("servermanager/server_list", array("server" => $liste));
            }
            break;
        case 'myserver':
            $server   = db("SELECT * FROM server WHERE server_id = ".sqlInt($_GET['id'])." AND server_owner = ".$_SESSION['userid'],'object');
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
                                "aviable_commands_text" => $aviable_commands_text[$count]
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
                        'id' => $server->server_type,
                        'type' => $server->server_type,
                        'host' => $server->server_ip.':'.$server->server_port,
                         );
                    
                    $gq = new GameQ();
                    $gq->addServer($server_query);
                    $gq->setOption('timeout', 4);
                    $gq->setFilter('normalise');
                    $results = $gq->requestData();

                    show(
                            "servermanager/server_show",
                            array(
                                "server_ip" => $results[$server->server_type]['gq_hostname'],
                                "server_port" => $results[$server->server_type]['gq_port'],
                                "server_joinlink" => $results[$server->server_type]['gq_joinlink']
                                )
                        );
                            
                    break;
            } 
            break;
    }
}
else
{
    $content = msg(_no_server_found);
}

init($content,$meta);