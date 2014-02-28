<?php
function counter() {

	//GET User IP
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$user_exist = db("SELECT ip FROM counter_user Where ip LIKE ".sqlString($user_ip),true);
	
	//Update
	if(!$user_exist) 
		db("INSERT INTO counter_user (id,ip, datum) VALUES (NULL,".sqlString($user_ip).", ".time().")");
	db("UPDATE counter_user SET datum=".time()." Where ip LIKE ".sqlString($user_ip));
	db("UPDATE counter SET aufrufe=aufrufe+1 Where id = 3");
	
	//Read
	$countToday = mysqli_fetch_object( db("SELECT aufrufe as countToday FROM counter Where id = 3"));
	$countTotal = mysqli_fetch_object( db("SELECT SUM(aufrufe) as countTotal FROM counter "));
	$usersTotal = mysqli_fetch_object( db("SELECT COUNT(ip) as usersTotal FROM counter_user"));

	//Output
	$output = show("panels/counter", array(	"HeadCounter" =>"Counter",
											"sCurrentlyOnline" => "aktuell Online:",
											"currentlyOnline" => "X-X",
											"sTodayMax" => "heute max. Online:",
											"todayMax" => "XX-X",
											"sUsersToday" => "heute Busucher:",
											"usersToday" =>	"XXX-X",
											"sUsersTotal" => "insg. Besucher:",					
											"usersTotal" =>	$usersTotal -> usersTotal,
											"sCountToday" => "Aufrufe:",
											"countToday" => $countToday->countToday,
											"sCountTotal" => "Insgesamt:",
											"countTotal" =>$countTotal->countTotal));

	return $output;
}