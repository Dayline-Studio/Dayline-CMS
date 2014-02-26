<?
include "../inc/config.php";

$file = show("msg/msg_stock");
$msg = constant("_".$_GET['id']);
$content = show ($file, array(	"msg" => $msg,
								"link" => $_SESSION['back_site']));
init($content);
?>
