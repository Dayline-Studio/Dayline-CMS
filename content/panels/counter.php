<?php
function counter() {

	//GET User IP
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$today = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
	$user_exist = db("SELECT ip FROM counter_user Where ip LIKE ".sqlString($user_ip),'rows');
	$day_exist = db("SELECT datum FROM counter Where datum LIKE ".sqlString($today),'rows');

	
	//Update
	if(!$user_exist) 
		up("INSERT INTO counter_user (id,ip, datum) VALUES (NULL,".sqlString($user_ip).", ".time().")");
	else 
	up("UPDATE counter_user SET datum=".time()." Where ip LIKE ".sqlString($user_ip));
	if(!day_exist)
		up("INSERT INTO counter (id,besucher, aufrufe, datum) VALUES (NULL,".sqlString($user_ip).", ".time().")");
	up("UPDATE counter SET aufrufe=aufrufe+1 Where id = 3");

	

	
	//Read
	$countToday = db("SELECT aufrufe as countToday FROM counter Where id = 3",'object')->countToday;
	$countTotal = db("SELECT SUM(aufrufe) as countTotal FROM counter ",'object')->countTotal;
	$usersTotal = db("SELECT COUNT(ip) as usersTotal FROM counter_user",'object')->usersTotal;
	$usersToday = db("SELECT COUNT(ip) as usersToday FROM counter_user WHERE datum>=".$today, 'object')->usersToday;

	//Output
	$output = show("panels/counter", array(	"HeadCounter" =>"Counter",
											"sCountToday" => "heutige Aufrufe:",
											"countToday" => $countToday,
											"sUsersToday" => "heutige Besucher:",
											"usersToday" =>	$usersToday,
											"sUsersTotal" => "insg.: Besucher:",					
											"usersTotal" =>	$usersTotal,
											"sCountTotal" => "insg.: Aufrufe",
											"countTotal" =>$countTotal));

	return $output;
}