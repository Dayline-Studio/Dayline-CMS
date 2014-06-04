<?php

// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
require_once ('mysql.php');

$_POST['salt']	= randomstring(32);	

$files = array();
$files[] = array('../inc/config.php',777);
$files[] = array('../inc/upload',777);
$files[] = array('../inc/_cache',777);

if (!isset($_GET['action'])) {
       $action = "";
} else {
       $action = $_GET['action'];
}

switch($action)
{
     default :
         header('Location: ?action=agb');
     break;
     case 'agb':
         $disp = file_get_contents("html/agb.html");
         break;
     case 'permissions': 
         $permissions_no_error = true;
			
		$case['permission_table'] = '';
		
         foreach($files as $path)
         {
             $case_tr['file'] = $path[0];
             $case_tr['is_file'] = is_file($path[1]) ? 'Datei' : 'Ordner';
             $case_tr['permission'] = $path[1];
             if( $path[1] != substr(sprintf('%o', fileperms($path[0])), -3))
             {
                
		$case['permission_table'] .= show(file_get_contents("html/permissions_tr_red.html"), $case_tr);
                $permissions_no_error = false;	
             } else {
                $case['permission_table'] .= show(file_get_contents('html/permissions_tr_green.html'), $case_tr);
             }
         }
         if ($permissions_no_error)
		 {
            $case['submit'] .= file_get_contents("html/permissions_true.html");
         } else {
			$case['submit'] = '';		
		}
		 
                $disp = 'Sehr geehrter Herr Admin, es wird empfohlen, dass alle Dateien oder Ordner, welche unter diesem Text vermerkt wurden die entsprechenden Rechte, welche ebenfalls vermerkt wurden, gesetzt sind, wurden.';
		$disp .= show(file_get_contents("html/permissions.html"), $case);
       
         break;
    case 'mysql_connection':
        $disp = file_get_contents("html/install.html");
        break;
    case 'check_input':
        $action = 'mysql_connection';
        if($dblink = mysqli_connect($_POST['sql_host'],$_POST['sql_user'],$_POST['sql_pass'],$_POST['sql_db']))
        {
            foreach($tables as $table => $sql)
            {
                if(mysqli_query($dblink, 'DROP TABLE IF EXISTS '.$table)) {
                        $disp .= "Datenbank " .$table. " wurde gel�scht, weil sie bereits vorhanden war<br>";
                }
                if(mysqli_query($dblink, 'CREATE TABLE '.$table.' ('.$sql.') ')) {
                        $disp .= "Datenbank " .$table. " wurde erfolgreich erstellt <br>";
                } else {
                        $disp .= "Erstellen der Datenbank ".$table." fehlgeschlagen.<br>";       
                }
            }
            if(!isset(
                    $_POST['slq_host']) || !isset($_POST['sql_user'])||
                    !isset($_POST['sql_pass']) || !isset($_POST['sql_db']))
            {
                $disp .="Bitte alle Felder ausfüllen!<br>";
            }
        }
        else echo 'Nein';
        $disp .= file_get_contents("html/check_input.html");
        break;
    case 'create_config':
        $content = show(file_get_contents('config.clear'),$_POST);

        $config_file = fopen ('config.php','r+');
        rewind($config_file);
        fwrite ($config_file, $content);
        fclose ($config_file);
        break;
}

$config = @simplexml_load_file("config.xml");

$case['version'] = $config->version; //platzhalter {version}
$case['released'] = 321432; //platzhalter {released}
$case['build'] = 0000.5;

$meta['ucp'] = show(file_get_contents('html/version.html'), $case);

$navi_lang = array(
    'agb' => 'Lizensbedingungen',
    'permissions' => 'Dateirechte',
    'mysql_connection' => 'Datenbank verbindung',
    'create_config' => 'Erstellen der Config'
    );

$meta['navi'] = "";
foreach($navi_lang as $sitename => $language)
{
    if ($sitename == $action) {
        $file = "naviagtion_tr_selected";
		$meta['title'] = $language;
    }
    else {
       $file = "navigation_tr";
    }
    $meta['navi'] .= show(file_get_contents('html/'.$file.'.html'),array('text' => $language));
	
}
$meta['navi'] = show(
        file_get_contents('html/navigation.html'),
        array(
            'navi' => $meta['navi']
        ));

Disp::addMeta($meta);
Disp::$content = $disp;
Disp::renderMinStyle();
