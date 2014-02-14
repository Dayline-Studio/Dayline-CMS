<?

function init($content)
{
	
	//$content = $path['style_index'];

	//Funktionen aus Style auslesen:
	preg_match_all('/\{(.+?)\}/',$content,$loading);
	
	//for ($i = 0;$i < sizeOf($loading); $i++)
	//{
	//	echo $loading[0][0];
	//}
	//print_r ($content);
	echo $loading[0][0][0];
	//Ausgabe
	//echo errorDisplay();
	//echo $content;
}
?> 
  