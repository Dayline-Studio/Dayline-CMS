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
                    $liste .= show("servermanager/server_list_li", array("server_name" =>  $server['server_name'],
                                                                         "server_ip" => $server['server_ip'],
                                                                         "server_kind" => ucfirst($server['server_kind']),
                                                                         "server_query_port" => $server['server_query_port'],
                                                                         "server_interface" => $server["server_interface"],
                                                                         "server_type" => ucfirst($server['server_type']),
                                                                         "id" => $server["id"],));
                }
                $content = show("servermanager/server_list", array("server" => $liste));
            }
            break;
        case 'myserver':
            
            break;
    }
}
else
{
    $content = msg(_no_server_found);
}

init($content,$meta);