<?
function init($content)
{
	//Global Path Variable
	global $path;
	
	//Loading Plugin Folder
	$plugins=opendir($path['plugins']);
	
	//Get the Main File for this Page (index.html)
	$output = getFile($content);
	
	//Loading the Plugins
	while ($plugin = readdir($plugins)) 
	{
		//Loading Plugin functions
		if ($plugin != ".." && $plugin != ".") 
		{
			debug("loading $plugin");
			//Import plugin function
			includeFile($path['plugins'].$plugin);
			//Remove .php
			$plugin = substr($plugin,0,-4);
			//Replace the Tag with the returning content from the plugin
			$output = show($output, array( $plugin => $plugin()));
			debug("[".ucwords($plugin)."] - checked and loaded");
		}
	}
	closedir($plugins);
	
	//Output Debug, Errors and Content
	echo debugOutput();
	echo $output;
}

function debugOutput()
{
	//Display alle Errors and the Log
	global $debug;
	$output = "Log:<br>";
	$output .= $debug;
	$output .= "Errors:<br>";
	if (errorDisplay() != "") $output .=  errorDisplay();
	else $output .= "Jawoll keine Errors!";
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
  
