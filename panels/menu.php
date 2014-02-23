<?
function menu()
{
	//Mainmenu content items
	$items = generator(0);
	//User Menu
	if ($_SESSION['loggedin'])
	{
		$items_ucp .= show("panels/menu_item", array( 'title' => "Logout",
												  'link' => "../pages/ucp.php?do=logout"));
		$items .= show("panels/menu_sub", array( 'title' => "UCP",
												  'items' => $items_ucp,
												  'link' => "../pages/ucp.php"));
	}
	
	$menu =  show("panels/menu",array( 'items' => $items,
								  'link_index' => '#'
								));
								
	return $menu;
}

function generator($subfrom)
{
	$qury = db('SELECT * FROM menu WHERE subfrom = '.$subfrom);
	for  ($i=0;$get = mysqli_fetch_assoc($qury);$i++) {
		debug("generating Menuepart from ".$get['title']);
		if ($get['issub'])
			$menu .= show("panels/menu_sub", array( 'title' => $get['title'],
													'link' => $get['link'],
													'items' => generator($get['id'])));
		else
			$menu .= show("panels/menu_item", array( 'title' => $get['title'],
													 'link' => $get['link']));
	}
	return $menu;
}
?>