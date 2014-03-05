 <?php
 function ucp(){
 
	if (!$_SESSION['loggedin'])
	{
		$login_panel = show("panels/login");
	} 
	else
	{
		$gravatar = get_gravatar($_SESSION['email'], 100, false);
		$login_panel = show("panels/ucp_main", array( "user" => $_SESSION['name'],
													   "Gravatar" => $gravatar));
	}
	
	

	
	
	
	return $login_panel;
 }