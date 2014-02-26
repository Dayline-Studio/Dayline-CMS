<?
function init($content = "", $meta)
{
	global $path, $language; 
	//debug("start init content");
	$init = show(loadingPanels(),array("content" => $content));
	$settings = mysqli_fetch_object(db("Select * from settings where id = 1"));
	$init = show($init,array(	"title" =>				$meta['title'],
								"language_content" =>	$language,
								"author" =>				$meta['author'],
								"publisher" =>			$settings->publisher,
								"copyright" =>			$settings->copyright,
								"keywords" =>			$meta['keywords'],
								"description" =>		$meta['description'],
								"language" =>			$settings->language,
								"css" => 				$path['css'],
								"js"  => 				$path['js']));
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
	
	//debug("loading panels");
	//Loading the panels
	while ($panel = readdir($panels)) 
	{
		//Loading panel functions
		if ($panel != ".." && $panel != ".") 
		{
			//debug("loading $panel");
			//Import panel function
			includeFile($path['panels'].$panel);
			//Remove .php (-4 chars)
			$panel = substr($panel,0,-4);
			//Replace the Tag with the returning content from the panel
			if (!function_exists ($panel))
			{
				//debug('<font color="#FF4000">failed loading '.$panel.'</font>');
			}
			else
			{
				$output = show($output, array( $panel => $panel() ));
				//debug('<font color="#2ECCFA">['.ucwords($panel)."]</font> - checked and loaded");
			}
		}
	} 
	closedir($panels);
	
	//Output Debug, Errors and Content
	//$handle = fopen('../debug/log.html', "w");
	//fwrite($handle, debugOutput()); 				
	//fclose($handle);

	return $output;
}

function debugOutput()
{
	//Display alle Errors and the Log
	global $debug;
	$output = '<meta http-equiv="refresh" content="3"> <iframe src="../debug/error_log" width="100%" height="200px" name="SELFHTML_in_a_box">
	  <p>Ihr Browser kann leider keine eingebetteten Frames anzeigen:
	  Sie k&ouml;nnen die eingebettete Seite &uuml;ber den folgenden Verweis
	  aufrufen: <a href="../../../index.htm">SELFHTML</a></p>
	</iframe>';
	$output .= "<hr>Log:<br>";
	$output .= $debug;
	$output .= "Errors:<br>";
	if (errorDisplay() != "") 
	{
		$output .=  '<font color="#ff0000">'.errorDisplay().'</font>';
	}
	else $output .= '<font color="#47ff00">keine Errors!</font>';
	return $output;
}

function debug($add)
{
	//function to add Debug inputs
	global $debug;
	//$debug .= '<font color="#FE9A2E">'."[".gmdate("H:i:s", time())."]</font> - ".$add."<br/>";
}
?>