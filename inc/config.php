<?php	
        require_once("../inc/auth.php");
                
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
        $path['dir'] = './';
        $path['include'] = "../inc/";
        $path['images'] = $path['include']."images/"; 
        $path['plugins'] = "../plugins/"; 
        $path['pages'] = "../pages/"; 
        $path['panels'] = "../panels/"; 
        $file['functions'] = $path['include']."functions.php";
        $file['auth'] = $path['include']."auth.php";
        $file['init'] = $path['include']."init.php";
        $path['lang'] = $path['include']."language/";
        $file['mysql'] = $path['include']."mysql.php";
	
	require_once($file['mysql']);
        if ($_SESSION['pageswich'] == 'd4ho') {
            $db_con['db'] = 'usr_db31_2';
        }
        $settings = db("select * from settings",'object');
        $style = $settings->style;
        
        $path['style'] = "../style/".$style."/";
        $path['css'] = $path['style']."_css/";
        $path['js'] = $path['style']."_js/";
        $path['style_index'] = $path['style']."index.html";
        
	require_once($file['functions']);

	//Loading Language
	includeFile($path['lang']."global.php");	
	
	$lang_dir = opendir($path['lang'].$language);
	
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
    
            global $db_con;
            if($db_con['host'] != '' && $db_con['user'] != '' && $db_con['pass'] != '' && $db_con['db'] != '')
            {
                    if(!$db_link = mysqli_connect($db_con['host'],$db_con['user'],$db_con['pass'], $db_con['db'])) {
                        error("Fehler beim Zugriff auf die Datenbank!");
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
    if(array_key_exists('_stmt_rows_', $fetch))
        return $fetch[0];
    else
        return $fetch->fetch_assoc();
}
    
    function db($input = "", $mysqli_action = null)
    {
            if(!$qry = mysqli_query( dbConnect(), $input )) {
                error('<b>Query</b>   = '.str_replace($path['prefix'],'',$input).'</ul>');
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
                    case 'assoc':
                        $qry = mysqli_fetch_assoc($qry);
                        break; 
                }
            } 
            return ($qry);
    }

    function up($input = "")
    {
            if(!mysqli_query( dbConnect(), $input )) {
                return false;
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