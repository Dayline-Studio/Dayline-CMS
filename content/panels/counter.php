<?php
function counter() {

	//GET User IP
	$user_ip = $_SERVER['REMOTE_ADDR'];
	$today = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
	$user_exist = db("SELECT ip FROM counter_user Where ip LIKE ".sqlString($user_ip),'rows');
	$day_exist = db("SELECT datum FROM counter Where datum LIKE ".$today,'rows');
        $today_online = db("SELECT ip FROM counter_user Where ip LIKE ".sqlString($user_ip)." and datum>=".$today,'rows');

	//Update
	if($user_exist == 0)
		up("INSERT INTO counter_user (id,ip, datum) VALUES (NULL,".sqlString($user_ip).", ".time().")");
	else
                up("UPDATE counter_user SET datum=".time()." Where ip LIKE ".sqlString($user_ip));
	if($day_exist == 0)
		up("INSERT INTO counter (id,besucher, aufrufe, datum) VALUES (NULL,0,1,".$today.")");
	else
	up("UPDATE counter SET aufrufe=aufrufe+1 Where datum LIKE ".$today);
        
        if($today_online == 0)
        up("UPDATE counter SET besucher=besucher+1 Where datum LIKE ".$today);

	//Read
	$count_today = db("SELECT aufrufe as count_today FROM counter WHERE datum=".$today,'object')->count_today;
	$count_total = db("SELECT SUM(aufrufe) as count_total FROM counter ",'object')->count_total;
	$users_total = db("SELECT SUM(besucher) as users_total FROM counter",'object')->users_total;
	$users_today = db("SELECT besucher as users_today FROM counter Where datum =".$today, 'object')->users_today;
        $daily_avg = db("SELECT AVG(aufrufe) as daily_avg FROM counter ",'object')->daily_avg;

	//Output
	$output = show("panels/counter", array(
                                    "count_today" => $count_today,
                                    "users_today" => $users_today,
                                    "users_total" => $users_total,
                                    "count_total" => $count_total,
                                    "daily_avg" => (int)$daily_avg));
	return $output;
}