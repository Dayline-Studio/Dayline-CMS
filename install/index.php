<?php

// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------
require_once ('mysql.php');

$_POST['salt']	= randomstring(32);	

$files = array();
$files['config'] = '../inc/config.php';
$files['upload'] = '../inc/upload';
$files['cach'] = '../inc/_cache';

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

         foreach($files as $path)
         {
             if( 777 != substr(sprintf('%o', fileperms($path)), -3))
             {
                 show ;
                 $disp .= $path.' muss auf 777 gesetzt werden. <br/>';
                 $permissions_no_error =false;	
             } else {
                 $disp .= $path.' wurde auf 777 gesetzt. <br/>';
             }

         }
         if ($permissions_no_error) {
             $disp  .= ' Permissions wurden richtig gesetzt<br>';   
             $disp .= file_get_contents("html/permissions.html");

         }
         $disp .= 'Permissions wurden nicht richtig gesetzt<br>';
         $disp .= file_get_contents("html/reload.html");
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
