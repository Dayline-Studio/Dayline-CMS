<?php
        require_once("../inc/ErrorHandler.php");
        require_once("../inc/DirControl.php");
	include("../inc/auth.php");
        require_once 'cachesystem/fastcache.php';
        
        $cmsError = new ErrorHandler();
        $cmsDir = new DirControl();
        echo $cmsDir->getPath('include');
                
//Parameter auslesen für allegemeine Settings
        if (isset($_GET['s'])) { $style = $_GET['s']; }
        else { $style = "default"; }
        if (isset($_GET['l'])) { $language = $_GET['l']; } 
        else { $language = "de"; }
        if (isset($_GET['do'])) { $do = $_GET['do']; }
        else { $do = ""; }
        if (isset($_GET['action'])) { $action = $_GET['action']; }
	else { $action = "";}
	if (isset($_GET['show'])) { $show = $_GET['show']; }
        else { $show = ""; }

//Allegmeine Pfade setzten durch Resultat der Parameter 
        $path['dir'] = '/test/';
        $path['include'] = "../inc/";
        $path['style'] = "../style/".$style."/";
        $path['css'] = $path['style']."_css/";
        $path['js'] = $path['style']."_js/";
        $path['style_index'] = $path['style']."index.html";
        $path['images'] = $path['include']."images/"; 
        $path['plugins'] = "../plugins/"; 
        $path['pages'] = "../pages/"; 
        $path['panels'] = "../panels/"; 
        $file['functions'] = $path['include']."functions.php";
        $file['auth'] = $path['include']."auth.php";
        $file['init'] = $path['include']."init.php";
        $path['lang'] = $path['include']."language/";
        $file['mysql'] = $path['include']."mysql.php";
	
	//Loading functions and init
	include($cmsDir->getFile('mysql'));
	include($cmsDir->getFile('functions'));	
	include($cmsDir->getFile('init'));
	
	//Loading Language
	includeFile($path['lang']."global.php");	
	
	$lang_dir = opendir($cmsDir->getPath('lang').$language);
	
	//Loading the panels
	if ( $language != 'de' || $language != 'en') 
        {
            $language = 'de'; //ToDo Language aus Settings (database)
        }
	while ($lang_file = readdir($lang_dir)) 
	{
		if ($lang_file != ".." && $lang_file != ".") 
		{
			//Import panel function
			includeFile($lang_dir.$language."/".$lang_file);
		}
	} 
	closedir($lang_dir);

	function includeFile($file)
	{
		$r = true;
		if (file_exists ( $file )) {
			return include($file);
		}
		else {
                        $cmsError->addError("File not found -".$file);
			$r = false;
		}
		return $r;
	}
	function getFile($file)
	{
		$r = true;
		if (file_exists ( $file )) {
			return file_get_contents($file);
		}
		else {
			$cmsError->addError("File not found -".$file);
			$r = false;
		}
		return $r;
	}