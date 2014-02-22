<?
function menu()
{
	$menu =  show("panels/menu",array( 'items' => generator(0),
								  'link_index' => '#'
								), true);
	return $menu;
}

function generator($subfrom)
{
	$qury = db('SELECT * FROM menu WHERE subfrom = '.$subfrom);
	for  ($i=0;$get = mysqli_fetch_assoc($qury);$i++) {
		debug("generating Menuepart from ".$get['title']);
		if ($get['issub'])
			$menu .= show("panels/menu_sub", array( 'title' => $get['title'],
													'items' => generator($get['id'])), true);
		else
			$menu .= show("panels/menu_item", array( 'title' => $get['title']) , true);
	}
	return $menu;
}
?>