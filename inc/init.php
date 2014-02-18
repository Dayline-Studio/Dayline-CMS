<?
$debug = "";
function init($content)
{
	//Loading plugin modules
	global $path;
	$plugins=opendir($path['plugins']);
	$output = getFile($content);
	while ($plugin = readdir($plugins)) 
	{
		//Loading File funktion
		if ($plugin != ".." && $plugin != ".") 
		{
			debug("loading $plugin");
			includeFile($path['plugins'].$plugin);
			$plugin = substr($plugin,0,-4);
			$output = show($output, array( $plugin => $plugin()));
			debug("[".ucwords($plugin)."] - checked and loaded");
		}
	}
	closedir($plugins);
	echo debugOutput();
	echo $output;
}

function debugOutput()
{
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
	global $debug;
	$debug .= "[".gmdate("H:i:s", time())."] - ".$add."<br/>";
}
?> 
  