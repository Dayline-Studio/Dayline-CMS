<?php
// Include CMS System
/**--**/ include "../inc/config.php";
//------------------------------------------------
// Site Informations
/**--**/  $meta['title'] = "Seite";
/**--**/  $meta['page_id'] = 3;
//------------------------------------------------

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
            $author = "Written by ".$get_site->author." - ".date("F j, Y, g:i a",$get_site->date);
            if ($get_site->lastedit != "") {
            $edited = "Last edit by ".$get_site->editby." - ".date("F j, Y, g:i a",$get_site->lastedit);
            }
            $content = show($content, array(
                "title" => 	$get_site->title,
                "site_id" => 	$show,
                "edited" =>     $edited,
                "author" =>     $author,
                "content" => 	$get_site->content));
            //Loading Meta
            $meta['title'] 			=	$get_site->title;
            $meta['author']			=	$get_site->author;
            $meta['keywords']		=	$get_site->keywords;
            $meta['description']            =       $get_site->description;
    }
    else {
        $content = msg(_site_not_found);
    }
}
else {
    switch ($do)
    {
        case  'update':
            if (permTo('site_edit')){
                if (up("update sites Set content = '".mysql_real_escape_string($_POST['mce_0'])."', editby = ".sqlString($_SESSION['name']).", lastedit = ".time()." where title LIKE ".sqlString($show))){
                        $content = msg(_change_sucessful);
                } else {
                    $content = msg(_change_failed);
                }
            }
            else {
               $content = msg(_change_failed);
            }
            break;
    }
}
init($content,$meta);



function getSites($subfrom = 0)
{
	$qury = db('SELECT * FROM sites WHERE subfrom = '.$subfrom.' Order by position');
	$sites ="";
	while  ($get = _assoc($qury)) 
	{
		$issub = db('SELECT subfrom,title FROM sites WHERE subfrom = '.$get['id'], 'rows');
		if (!$issub)
		{
			if ($get['newtab']) {
                            $tab = 'target="_blank"';
                        }
			else {
                            $tab = '';
                        }
			$sites .= show("site/site_li", array('title' => $get['title'],
								'newtab' => $tab,
								'link' => getLink($get['title'])));
		}
		else
		{
			$sites .= show("site/site_sub_ul", array( 'title' => $get['title'],
                                                                'link' => getLink($get['title']),
								'items' => getSites($get['id'])));
		}
	}
	return $sites;
}

function getLink($title)
{
    return "../pages/site.php?show=".str_replace("_"," ",$title);
}