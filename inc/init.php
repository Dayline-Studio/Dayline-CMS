<?php
function init($content = "", $meta = null)
{
	global $path, $language; 
	$init = show(loadingPanels(),array("content" => $content));
	$settings = mysqli_fetch_object(db("Select * from settings where id = 1"));
	$init = show($init,array("title" =>                     $meta['title'],
                                 "language_content" =>          $language,
                                 "author" =>                    $meta['author'],
                                 "publisher" =>	                $settings->publisher,
                                 "copyright" =>                 $settings->copyright,
                                 "keywords" =>                  $meta['keywords'],
                                 "description" =>               $meta['description'],
                                 "language" =>                  $settings->language,
                                 "css" =>                       $path['css'],
                                 "style" =>                     $path['style'],
                                 "js" =>                        $path['js']));
        display($init);
}

function display($content)
{
	echo $content;
}
function loadingPanels()
{
	//Global Path Variable
	global $path;
	//Loading panel Folder
	$panels = opendir($path['panels']);
	//Get the Main File for this Page (index.html)
	$output = getFile($path['style_index']);
	
	//Loading the panels
	while ($panel = readdir($panels)) 
	{
		//Loading panel functions
		if ($panel != ".." && $panel != ".") 
		{
			//Import panel function
			require_once ($path['panels'].$panel);
			//Remove .php (-4 chars)
			$panel = substr($panel,0,-4);
			//Replace the Tag with the returning content from the panel
			if (function_exists($panel)){
                            $output = show($output, array( $panel => $panel() ));
                        }
		}
	} 
	closedir($panels);
        
	return $output;
}