<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = 'Nachricht';
//------------------------------------------------

$file = show("msg/msg_stock");
$msg = constant("_".$_GET['id']);
$content = show ($file, array(	"msg" => $msg,
				"link" => $_SESSION['back_site']));
init($content,$meta);
