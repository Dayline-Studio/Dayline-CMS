<?php

// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
 require_once ('mysql.php');
 
	$files = array();
	$files['config'] = '../inc/config.php';
	$files['upload'] = '../inc/upload';
	$files['cach'] = '../inc/_cache';
				
	switch($_GET['action'])
	{
		default: 
                    $permissions_no_error = true;
                   
			foreach($files as $path)
			{
                               
				if( 777 != substr(sprintf('%o', fileperms($path)), -3))
				{
                                    echo $path.' muss auf 777 gesetzt werden. <br/>';
                                       
                                         $permissions_no_error =false;	
				}
                                else
                                {
                                    echo $path.' wurde auf 777 gesetzt. <br/>';
                                }
                              
			}
                          if ($permissions_no_error)
                                {
                                    echo ' Permissions wurden richtig gesetzt<br>';   
                                     $disp = file_get_contents("html/permissions.html");
                                     echo($disp);
                                }
		break;
		
		case 'mysql_connection':
			$disp = file_get_contents("html/install.html");
			echo ($disp );	
		break;
		
		case 'check_input':
		    
			if(mysqli_connect($config['sql_host'],$config['sql_user'],	$config['sql_pass'],$config['sql_db']))
			{
				foreach($tables as $table => $sql)
				{
					if(up('DROP TABLE IF EXISTS '.$table))
					{
						echo "Datenbank " .$table. " wurde gel�scht, weil sie bereits vorhanden war<br>";
					}
					if(up('CREATE TABLE '.$table.' ('.$sql.') '))
					{
						echo "Datenbank " .$table. " wurde erfolgreich erstellt <br>";
					}	
					else
					{
						echo "Erstellen der Datenbank ".$table." fehlgeschlagen.<br>";
					}
				}
			}
			else echo 'Nein';
                        
                        $disp = file_get_contents("html/check_input.html");
                                     echo($disp);
		break;
		
		case 'create_config':
			$content = show(file_get_contents('config.clear'),$config);
			
			$config_file = fopen ('config.php','r+');
			rewind($config_file);
			fwrite ($config_file, $content);
			fclose ($config_file);
			
		break;
	
	}
	
