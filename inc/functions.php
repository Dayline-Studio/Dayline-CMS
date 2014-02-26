<?

	//Dateipfad und Tags[array] werden übergeben
	function show($file_content = "", $tags = array(null => null))
	{
		global $path;

		//Tags werden gesplittet einzeln durchgeführt

        if(file_exists($path['style']."/".$file_content.".html"))
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
		debug("Reading database");
		if($db_con['host'] != '' && $db_con['user'] != '' && $db_con['pass'] != '' && $db_con['db'] != '')
		{
			if(!$db_link = mysqli_connect($db_con['host'],$db_con['user'],$db_con['pass'], $db_con['db'])) error("Fehler beim Zugriff auf die Datenbank!");
			else return $db_link;
		}
		else error("Es wurden nicht alle Datenbank Daten zur Verbindung angegeben");
		return false;
	}
	
	function db($input = "", $rows = false, $fetch = false)
	{
		global $db_con, $path;     
		//$db_link = mysqli_connect($db_con['host'],$db_con['user'],$db_con['pass'],$db_con['db']);
		if(!$qry = mysqli_query( dbConnect(), $input )) error('<b>Query</b>   = '.str_replace($path['prefix'],'',$input).'</ul>');
		
		if ($rows && !$fetch)     return mysqli_num_rows($qry);
		else if($fetch && $rows)  return mysqli_fetch_array($qry);
		else if($fetch && !$rows) return mysqli_fetch_assoc($qry);
		
		return ($qry);
	}
	
	function randomstring($length = 6) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890";
		srand((double)microtime()*1000000);
		$i = 0;
		while ($i < $length) 
		{
			$num = rand() % strlen($chars);
			$tmp = substr($chars, $num, 1);
			$pass = $pass . $tmp;
			$i++;
		}
		return $pass;
	}
	
	function customHasher($pw, $salt, $rounds)
	{
		$hash = $pw;
		for ($i = 0; $i < $rounds; $i++)
		{
			if (!($i%3.5))
				$hash = sha1($hash.$salt);
			else
				$hash = md5($salt.$hash);
		}
		sha1($hash); 
		return $hash;
	}
	
	function sqlString($param) {
		return (NULL === $param ? "NULL" : '"'.mysql_real_escape_string($param).'"');
	}
 
	function sqlInt($param) {
		return (NULL === $param ? "NULL" : intVal ($param));
	}
	
	function get_gravatar( $email, $s = 80, $img = false) 
	{
		$d = 'wavatar';
		$r = 'g';
		$url = 'http://www.gravatar.com/avatar/';
		$url .= md5( strtolower( trim( $email ) ) );
		$url .= "?s=$s&d=$d&r=$r";
		if ( $img ) 
		{
			$url = '<img src="' . $url . '" />';
		}	
		return $url;
	}

	function msg($id,$back)
	{
		header('Location: '.$path['pages'].'msg.php?id='.$id);
	}
	
	function getNews($groupid = 0)
	{
		$news_posts = db("Select * From news where grp = ".$groupid);
		$list_news = "";
		while ($get_news = mysqli_fetch_assoc($news_posts))
		{
			$list_news .= show("news/post",array(	"news_headline"	=>	$get_news['title'],
												"news_date"		=> 	date("m.d.y",$get_news['date']),
												"news_content"	=>	$get_news['post']
											));
		}
		if ($list_news == "") $list_news = "Keine News Gefunden";
		return $list_news;
	}
	
	function setBackSite()
	{
		$_SESSION['back_site'] = getCurrentUrl();
	}
	
	function permTo($permission)
	{
		return	mysqli_fetch_object(db("SELECT ".$permission." From groups WHERE id = ".sqlInt($_SESSION['group_main_id'])))->$permission;
	}
?>