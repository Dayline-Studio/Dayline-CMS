<?
include "../inc/config.php";

if (isset($_GET['site'])) $site = $_GET['site'];
else $site = 1;

if (permTo('site_edit'))
	$content = "site/editor";
else
	$content = "site/output";

if ($get_site = mysqli_fetch_object(db("select * from sites where id Like ".sqlString($site))))
{
	$content = show($content, array(		"title" => 		$get_site->title,
											"content" => 	$get_site->content));
	//Loading Meta
	$meta['title'] 			=	$get_site->title;
	$meta['author']			=	$get_site->author;
	$meta['keywords']		=	$get_site->keywords;
	$meta['description']	=	$get_site->description;
}
else $content = msg('site_not_found');
								
switch ($do)
{
	case  'update':
		if (permTo('site_edit'))
		db("update sites Set title = '".$_POST['mce_0']."', content = '".$_POST['mce_2']."' where id = 1");
		msg(4);
	break;
}

init($content,$meta);
?>