<?
function init($content)
{
	debug("start init content");
	display(show(loadingPanels(),array("content" => $content)));
}

function display($content)
{
	debug("Ausgabe");
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
	
	debug("loading panels");
	//Loading the panels
	while ($panel = readdir($panels)) 
	{
		//Loading panel functions
		if ($panel != ".." && $panel != ".") 
		{
			debug("loading $panel");
			//Import panel function
			includeFile($path['panels'].$panel);
			//Remove .php (-4 chars)
			$panel = substr($panel,0,-4);
			//Replace the Tag with the returning content from the panel
			$output = show($output, array( $panel => $panel() ));
			debug("[".ucwords($panel)."] - checked and loaded");
		}
	} 
	closedir($panels);
	
	//Output Debug, Errors and Content
	$output = debugOutput().$output;
	return $output;
}

function debugOutput()
{
	//Display alle Errors and the Log
	global $debug;
	$output = "Log:<br>";
	$output .= $debug;
	$output .= "Errors:<br>";
	if (errorDisplay() != "") 
	{
		$output .=  '<font color="#ff0000">'.errorDisplay().'</font>';
	}
	else $output .= '<font color="#47ff00">keine Errors!</font>';
	$output .= "<hr>Output:<hr>";
	return $output;
}

function debug($add)
{
	//function to add Debug inputs
	global $debug;
	$debug .= "[".gmdate("H:i:s", time())."] - ".$add."<br/>";
}
?>