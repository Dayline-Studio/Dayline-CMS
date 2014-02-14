 <?
 //Parameter auslesen für allegemeine Settings
 	if (isset($_GET['s'])) $style = $_GET['s'];
	else $style = "default";
	if (isset($_GET['l'])) $style = $_GET['l']; 
	else $language = "de";
	if (isset($_GET['do'])) $do = $_GET['do'];
	if (isset($_GET['action'])) $action = $_GET['action'];

//Allegmeine Pfade setzten durch Resultat der Parameter 
	$error = "";
	$path['include'] = "../inc/";
	$path['style'] = "../style/".$style."/";
	$file['style_index'] = $path['style']."index.html";
	$path['images'] = $path['include']."images/"; 
	//$file['actions'] = "../pages/".$page."/index.php";
	//$file['language'] = "../inc/language/".$language.".php";
	$file['mysql'] = $path['include']."mysql.php";
	$file['functions'] = $path['include']."functions.php";
	$file['init'] = $path['include']."init.php";
	$STEAM_API_KEY = "90245BB467E201DE99CF36C6FD1ED9FA";
	 
//Sprachfiles und funktionen einsetzten die benötigt werden	
	includeFile($file['style_index']);
	includeFile($file['mysql']);
	includeFile($file['functions']);
	includeFile($file['init']);
	init("{test2}test{ztetsa}");
	//echo show($file['style'], init());
	
	//$qry = db("SELECT * FROM ".$db['settings']."");
	//$settings = _fetch($qry);	

	function includeFile($file)
	{
		$r = true;
		if (file_exists ( $file )) 
		{
			return include($file);
		}
		else 
		{
			error("File not found -".$file);
			$r = false;
		}
		return r;
	}
	
	function error($this_error)
	{
		global $error;
		$error .= $this_error;
	}
	
	function errorDisplay()	
	{
		global $error;
		return $error;
	}
 ?>