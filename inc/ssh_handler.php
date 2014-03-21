<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Housslave";
//------------------------------------------------

$start_time = time();
$server_id = $_POST['server_id'];
$server_todo = $_POST['todo'];
$server_input = $_POST['input'];

$qry = db("SELECT * FROM server");

$i = 0;
while ($zeile = _assoc($qry))
{   		
        if ($zeile['server_owner'] == $username){
                if ($server_id == $i){
                        $server_name = $zeile['server_name'];
                        $server_kind = $zeile['server_kind'];
                        $server_type = $zeile['server_type'];
                        $server_ip = $zeile['server_ip'];
                }		
        $i++;
        }
}

$count = db("SELECT run_id FROM server_run WHERE run_user LIKE '$server_name'",'rows'); 

if ($count == null)
{
        if ($server_kind == gameserver)
        {
                if ($server_type == minecraft || $server_type == hexxit){
                        if ($server_todo == server_interface_stop)
                        {			
                                $command = "stop";
                        }
                        if ($server_todo == server_interface_kill)
                        {
                                $command = "kill";
                        }
                        if ($server_todo == server_interface_start)
                        {
                                $command = "start";
                        }
                        if ($server_todo == server_interface_restart)
                        {
                                $command = "restart";
                        }
                }
        }
        else if ($server_kind == webserver)
        {
                if ($server_type == webspace)
                {
                        if ($server_todo == ftp_password_change)
                        {	
                                $save_for_the_root = ereg_replace("[^A-Za-z0-9?!@]", "", $server_input);
                                if (strlen($server_input) == strlen($save_for_the_root))
                                {
                                        if ($server_input != null) 
                                                $command = "pwchange/".$server_input;
                                }
                                else $output = "Im Passwort sind nur folgende Chars erlaubt: 1-9 a-z A-Z ! ? @";

                        }
                }		
        }
        else if ($server_kind == teamfortress2)
        {
                        if ($server_todo == server_stop)
                        {			
                                $command = "stop";
                        }
                        if ($server_todo == server_kill)
                        {
                                $command = "kill";
                        }
                        if ($server_todo == server_start)
                        {
                                $command = "start";
                        }
                        if ($server_todo == server_restart)
                        {
                                $command = "restart";
                        }
                        if ($server_todo == server_restart)
                        {
                                $command = "update";
                        }	
        }
        if ($command != null) 
        {
                $sql = "INSERT INTO `usr_dayline_1`.`server_run` (`run_command`, `run_id`, `run_host`, `run_user`, `run_kind`) VALUES ('$command', NULL, '$server_ip', '$server_name', '$server_type');";
                $db_erg = mysqli_query( $db_link, $sql );
                $output = "Dein Befehl wurde zum Server weitergeleitet. Dies kann maximal 60 Sekunden in Anspruch nehmen.";
        }			
}
else $output = "Es wird bereits ein anderer Befehl ausgeführt!";

init( "Houseslave: ".$output);