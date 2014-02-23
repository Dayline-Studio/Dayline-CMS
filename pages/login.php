<?
include "../inc/config.php";
include "../inc/auth.php";

switch ($show)
{
	default: 

	break;
	case 'register':

	break;
}
switch ($do)
{
	case 'login': 
      session_start();

      $username = $_POST['username'];
      $passwort = $_POST['passwort'];
	  
	  $user = mysqli_fetch_object(db('Select * From users Where user like '.sqlString(strtolower($username))));
	  
      if (customHasher($passwort,$user->salt,$user->rounds) == $user->pass) 
	  {
		   $_SESSION['loggedin'] = true;
		   $_SESSION['name'] = $user->name;
		   $_SESSION['user'] = $user->user;
		   $_SESSION['login_time'] = time();
		   header('Location: ../pages/ucp.php');
		   exit;
       }
      
	break;
	case 'register':

	break;
}
init($content);
?>