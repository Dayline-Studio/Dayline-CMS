<?
$debug = "";
function init($content)
{
	//Loading plugin modules
	global $path;
	$plugins=opendir($path['plugins']);
	while ($plugin = readdir($plugins)) {
	 //Loading File funktion
	 if ($plugin != ".." && $plugin != ".") 
	 {
		debug("loading $plugin");
	    includeFile($path['plugins'].$plugin);
		$plugin = substr($plugin,0,-4);
		debug("[".ucwords($plugin)."] - checked and loaded");
     }
	}
	closedir($plugins);
	echo debugOutput();
}

function debugOutput()
{
	global $debug;
	$output = "Log:<br>";
	$output .= $debug;
	$output .= "Errors:<br>";
	if (errorDisplay() != "") $output .=  errorDisplay();
	else $output .= "keine Errors :D";
	$output .= "<hr>";
	return $output;
}

function debug($add)
{
	global $debug;
	$debug .= "[".gmdate("H:i:s", time())."] - ".$add."<br/>";
}
?> 
  