<?
include "../inc/auth.php";
include "../inc/config.php";

switch ($do)
{
	case logout:
		 session_start();
		 session_destroy();

		 $hostname = $_SERVER['HTTP_HOST'];
		 $path = dirname($_SERVER['PHP_SELF']);

		 header('Location: http://'.$hostname.($path == '/' ? '' : $path).'/login.php');
	break;
}
$content = '<a href="../pages/ucp.php?do=logout">Logout</a>';
init($content);
?>