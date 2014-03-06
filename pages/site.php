<?php
include "../inc/config.php";

if ($do == "")
{
    if (permTo('site_edit')){
        $content = "site/editor";
    }
    else{
        $content = "site/output";
    }

    //show to id
    $show = str_replace("_"," ",$show);

    if ($get_site = db("select * from sites where title Like ".sqlString($show),'object'))
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
}
else {
    switch ($do)
    {
        case  'update':
            if (permTo('site_edit')){
                echo sqlString($show);
            if (up("update sites Set content = '".$_POST['mce_0']."' where title LIKE ".sqlString($show))){
                    $content = msg('change_sucessful');
            }
            else $content = msg('change_failed');
            }
            else{
               $content = msg('change_failed');
            }
            break;
    }
}
init($content,$meta);