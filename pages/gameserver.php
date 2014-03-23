<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Server";
//------------------------------------------------

require '../plugins/gameq/GameQ.php';

$cache = phpFastCache("files");
$content = $cache->get("servers");

if ($content == null)
{
    $qry_servers = db("select ip,port,type FROM gameserver");
    for ($i=0;$server = _assoc($qry_servers);$i++)
    {
        $servers[$i] = 
            array(
                    'id' => $server['type'].$i,
                    'type' => $server['type'],
                    'host' => $server['ip'].':'.$server['port'],
            );
    }

    $gq = new GameQ();
    $gq->addServers($servers);
    $gq->setOption('timeout', 4);
    $gq->setFilter('normalise');
    $results = $gq->requestData();
    $content ="";

    foreach($results as $server)
    {
         $ip = $server['gq_address'].':'.$server['gq_port'];
        if (!empty($server['gq_joinlink'])){
           $ip = '<a href="'.$server['gq_joinlink'].'">'.$ip.'</a>';
        }
            
        $content .= show("gameserver/server", 
                        array(
                                "hostname" => $server['gq_hostname'],
                                "ip" => $ip,
                                "map" => $server['map'],
                                "game" => $server['gq_type'],
                                "players" => ($server['gq_numplayers']).'/'.$server['gq_maxplayers'])
                            );
    }
    $cache->set("servers", $content, 1);
}

init($content,$meta);

