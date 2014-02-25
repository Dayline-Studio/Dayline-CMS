<?
include "../inc/config.php";

$file = show("msg/msg_stock");
switch ($_GET['id'])
{
	case 1:
		$msg = _pass_dont_match;
	break;
	case 2:
		$msg = _pass_wrong;
	break;
	case 3:
		$msg = _fields_missing;
	break;
	case 4:
		$msg = _change_sucessful;
	break;
}

$content = show ($file, array(	"msg" => $msg,
								"link" => $_SESSION['back_site']));

init($content);
?>
