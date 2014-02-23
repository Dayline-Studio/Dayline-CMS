 <?
 function ucp(){
 
	if (!$_SESSION['loggedin'])
	{
		$login_panel = show("panels/login");
	}
	else
	{
		$login_panel = "Willkommen ".$_SESSION['name'];
	}
	

	
	
	
	return $login_panel;
 }
 ?>