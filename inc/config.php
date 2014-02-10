 <?
 //Parameter auslesen für allegemeine Settings
 	if (isset($_GET['s'])) $style = $_GET['s'];
	else $style = "default";
	if (isset($_GET['p'])) $style = $_GET['p'];
	else $page = "index";
	if (isset($_GET['l'])) $style = $_GET['l'];
	else $language = "de";
	if (isset($_GET['do'])) $do = $_GET['do'];
	if (isset($_GET['action'])) $action = $_GET['action'];

//Allegmeine Pfade setzten durch Resultat der Parameter 
	$path['include'] = "../inc/";
	$path['style'] = "../style/".$style;
	$path['images'] = $path['include']."images/"; 
	//$file['actions'] = "../pages/".$page."/index.php";
	$file['language'] = "../inc/language/".$language."/".$page.".html";
	$file['mysql'] = $path['include']."mysql.php";
	$file['functions'] = $path['include']."functions.php";
	$STEAM_KEY = "90245BB467E201DE99CF36C6FD1ED9FA";
	 
//Sprachfiles und funktionen einsetzten die benötigt werden
	if (file_exists ( $file['mysql'])) 		include( $file['mysql'] );
	else echo("File not found -" .$file['mysql']."<br/>");
	if (file_exists ( $file['language'] ))	include( $file['language'] );
	else echo("File not found -" .$file['language']."<br/>");	
	//if (file_exists ( $file['functions']))	include( $file['actions'] );
	//else echo("File not found -" .$file['actions']."<br/>");
	if (file_exists ( $file['functions']))	include( $file['functions'] );
	else echo("File not found -" .$file['functions']."<br/>");

	//echo show($file['style'], init());
	
	//$qry = db("SELECT * FROM ".$db['settings']."");
	//$settings = _fetch($qry);	
	
 ?>