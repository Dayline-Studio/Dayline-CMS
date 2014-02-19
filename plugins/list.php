<?
function list()
{
global $path;

$eintraege = array ('eintrag1', "eintrag2", "eintrag3");
foreach($eintraege as $input)
{
	 $list .= show(file_get_contents($path['style']."/menue/main_content.html"), array("menueeintrag" => $input) );
}
$output = show(file_get_contents($path['style']."/menue/main.html"), array("main_conent" => $list) );




return $output;
}
?>