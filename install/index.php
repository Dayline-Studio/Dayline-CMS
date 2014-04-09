<?php

// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
 require_once ('mysql.php');
 
	$config['sql_host'] ='localhost';
	$config['sql_db'] = 'usr_db31_2';
	$config['sql_user']= 'db31';
	$config['sql_pass'] = 'XaG8Vv2bUnKVdMzm'; 
	$config['salt']	= randomstring(32);	
				
	switch($_GET['action'])
	{
		default:
			$disp = file_get_contents("html/install.html");
			echo ($disp );	
		break;
		
		case check_input:
		    
			if(mysqli_connect($config['sql_host'],$config['sql_user'],	$config['sql_pass'],$config['sql_db']))
			{
				foreach($tables as $table => $sql)
				{
					if(up('DROP TABLE IF EXISTS '.$table))
					{
						echo "Datenbank " .$table. " wurde gelöscht, weil sie bereits vorhanden war<br>";
					}
					if(up('CREATE TABLE '.$table.' ('.$sql.') '))
					{
						echo "Datenbank " .$table. " wurde erfolgreich erstellt<br>";
					}	
					else
					{
						echo "Erstellen der Datenbank ".$table." fehlgeschlagen.<br>";
					}
				}
			}
			else echo 'Nein';
		break;
		
		case create_config:
			$content = show(file_get_contents('config.clear'),$config);
			
			$config_file = fopen ('config.php','r+');
			rewind($config_file);
			fwrite ($config_file, $content);
			fclose ($config_file);
			echo asd;
		break;
	
	}
	
