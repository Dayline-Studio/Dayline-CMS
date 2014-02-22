 <?
 function login(){
 
	$login_panel = show("panels/login",null,true);
	$login_panel = show("panels/main",array( 'main_content' => $login_panel),true);
	return $login_panel;
 }
 ?>