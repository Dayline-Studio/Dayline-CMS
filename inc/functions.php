<?

	//Dateipfad und Tags[array] werden übergeben
	function show($file_content, $tags, $isPath = false)
	{
		global $path;

		//Tags werden gesplittet einzeln durchgeführt
		if ($isPath)
		{
			debug($path['style'].$file_content.".html");
			$file_content = getFile($path['style']."/".$file_content.".html");		
		}
		foreach($tags as $name => $value)
		  {
			 //Tags der Datei werden ersetzt durch funktionen und Sprachelemente
			 $file_content = str_replace('['.$name.']', $value, $file_content);
		  }
		//Datei wird fertig generiert zurück gegeben.
		return $file_content;
	}
	
	function dbConnect()
	{
	
		global $db_con;
//		echo($db_con['host']);
		debug("Connecting to db");
		if($db_con['host'] != '' && $db_con['user'] != '' && $db_con['pass'] != '' && $db_con['db'] != '')
		{
			if(!$db_link = mysqli_connect($db_con['host'],$db_con['user'],$db_con['pass'], $db_con['db'])) error("Fehler beim Zugriff auf die Datenbank!");
			else return $db_link;
		}
		else error("Es wurden nicht alle Datenbank Daten zur Verbindung angegeben");
		debug("Database Connected");
		return false;
	}
	
	function db($input = "", $rows = false, $fetch = false)
	{
		global $db_con;     
		//$db_link = mysqli_connect($db_con['host'],$db_con['user'],$db_con['pass'],$db_con['db']);
		if(!$qry = mysqli_query( dbConnect(), $input )) error('<b>Query</b>   = '.str_replace($path['prefix'],'',$input).'</ul>');
		
	if ($rows && !$fetch)     return mysqli_num_rows($qry);
	else if($fetch && $rows)  return mysqli_fetch_array($qry);
	else if($fetch && !$rows) return mysqli_fetch_assoc($qry);
	
	return ($qry);
	}


?>