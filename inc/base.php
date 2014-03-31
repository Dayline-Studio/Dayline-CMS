<?php	
        require_once("../inc/auth.php");
        require_once("../inc/config.php");
        
//Parameter auslesen für allegemeine Settings
        if (isset($_GET['s'])) { $style = $_GET['s']; }
        else { $style = "default"; }
        if (isset($_GET['l'])) { $language = $_GET['l']; } 
        else { $language = "de"; }
        if (isset($_GET['show'])) { $show = $_GET['show']; }
        else { $show = ""; }    
        if (isset($_GET['do'])) { $do = $_GET['do']; }
        else { $do = ""; }
        if (isset($_GET['action'])) { $action = $_GET['action']; }
	else { $action = "";}    
        
//Allegmeine Pfade setzten durch Resultat der Parameter 
        $path['dir'] = $config['dir'];
        $path['content'] = "../content/";
        $path['include'] = "../inc/";
        $path['upload'] = $path['include']."upload/";
        $path['images'] = $path['include']."images/"; 
        $path['plugins'] = $path['content']."plugins/"; 
        $path['pages'] = "../pages/"; 
        $path['panels'] = $path['content']."panels/"; 
        $file['functions'] = $path['include']."functions.php";
        $file['auth'] = $path['include']."auth.php";
        $file['init'] = $path['include']."init.php";
        $path['lang'] = $path['content']."language/";
        
        $settings = db("select * from settings",'object');
        $style = $settings->style;
        
        $path['style'] = $path['content']."/style/".$style."/";
        $path['css'] = $path['style']."_css/";
        $path['js'] = $path['style']."_js/";
        $path['style_index'] = $path['style']."index.html";
        
	require_once($file['functions']);
        
	//Loading Language
	includeFile($path['lang']."global.php");	
	
	
	if ( $language != 'de' || $language != 'en') {
            $language = $settings->language;
    }
		
	$lang_dir = opendir($path['lang'].$language);
	while ($lang_file = readdir($lang_dir)) {
		if ($lang_file != ".." && $lang_file != ".") {
			include($path['lang'].$language.'/'.$lang_file);
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
                        addError("File not found -".$file);
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
			addError("File not found -".$file);
			$r = false;
		}
		return $r;
	}
        
        
            function dbConnect()
    {
    
            global $config;
            if($config['sql_host'] != '' && $config['sql_user'] != '' && $config['sql_pass'] != '' && $config['sql_db'] != '')
            {
                    if(!$db_link = mysqli_connect($config['sql_host'],$config['sql_user'],$config['sql_pass'], $config['sql_db'])) {
                        die("Fehler beim Zugriff auf die Datenbank!");
                    }
                    else {
                        return $db_link;
                    }
            }
            else {
                error("Es wurden nicht alle Datenbank Daten zur Verbindung angegeben");
            }
            return false;
    }
    function _assoc($fetch)
{
    if(array_key_exists('_stmt_rows_', $fetch)) {
        return $fetch[0];
    }
    else {
        return $fetch->fetch_assoc();
    }
}
    
    function db($input = "", $mysqli_action = null)
    {
            if(!$qry = mysqli_query( dbConnect(), $input )) {
                die($input);
            }           

            if ($mysqli_action != null)
            {
                switch ($mysqli_action)
                {
                    case 'array':
                        $qry = mysqli_fetch_array($qry);
                        break;
                    case 'rows':
                        $qry = mysqli_num_rows($qry);
                        break;
                    case 'object':
                        $qry = mysqli_fetch_object($qry);
                        break;
                }
            } 
            return ($qry);
    }

    function up($input = "")
    {
            if(!mysqli_query( dbConnect(), $input )) {
                die($input);
            }
            return true;
    }
    
        function error($error)
    {
        global $errors;
        $errors .= $error."<br>";
    }
    
    function getErrors()
    {
        global $errors;
        return $errors;
    }