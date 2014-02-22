<?
function menu()
{
	
	/*$qury1 = db('SELECT * FROM menu WHERE id = 1');
	for  ($i=0;$get1 = mysqli_fetch_assoc($qury1);$i++) {
		$qury1 = db('SELECT * FROM menu WHERE subfrom = '.$get1['id']);
		for  ($k=0;$get2 = mysqli_fetch_assoc($qury2);$k++) {
	
		}
	}*/
	
	$items = show("panels/menu_item", null, true);
	$items = show("panels/menu_sub", array( 'items' => $items), true);
	$menu = show("panels/menu",array( 'items' => $items,
									  'link_index' => '#'
									), true);
	return $menu;
}
?>