<?php
include "../inc/config.php";

if (permTo('site_edit')){
    $content = "site/editor";
}
else{
    $content = "site/output";
}

//show to id
$show = str_replace("_"," ",$show);
	
if ($get_site = mysqli_fetch_object(db("select * from sites where title Like ".sqlString($show))))
{
	$content = show($content, array("title" => 	$get_site->title,
					"site_id" => 	$show,
					"content" => 	$get_site->content));
	//Loading Meta
	$meta['title'] 			=	$get_site->title;
	$meta['author']			=	$get_site->author;
	$meta['keywords']		=	$get_site->keywords;
	$meta['description']            =       $get_site->description;
}
else {
    $content = msg('site_not_found');
}
								
switch ($do)
{
    case  'update':
        if (permTo('site_edit')){
        if (db("update sites Set title = '".$_POST['mce_0']."', content = '".$_POST['mce_2']."' where id = ".sqlInt($show))){
                msg('change_sucessful');
            }
        }
        else{
            msg('change_failed');
        }
        break;
}

init($content,$meta);