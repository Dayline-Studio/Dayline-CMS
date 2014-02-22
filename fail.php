<?
	$db = db('SELECT * FROM artikel',true,false);
	//while($zeile = (db('SELECT * FROM artikel',true,false)))
	//{
		//echo $zeile['id'];
	//}

		print_r ($db);
		
function db($sql,$fetch,$row)
{
	//global mysql;
	$mysql['host']='localhost';
	$mysql['name']= 'usr_db31_1';
	$mysql['user']= 'db31';
	$mysql['password']= 'wZYK7BSKMRm4T2tY';
	
	
	$db_link = mysqli_connect($mysql['host'],$mysql['user'],$mysql['password'],$mysql['name']);
	$db_erg = mysqli_query( $db_link, $sql );
	
	if ($fetch) $db_erg = mysqli_fetch_object($db_erg);
	return $db_erg;
	echo "done";
}
	
	//by Moee
?>