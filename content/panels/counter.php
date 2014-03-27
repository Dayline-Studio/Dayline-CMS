<?php
function counter() {

	$today = mktime(0,0,0,date("m",time()),date("d",time()),date("Y",time()));
	//update
		//updatet die besucherzahl+1 wenn kein heutiger eintrag des users existiert
		up("UPDATE counter SET besucher=besucher+1 WHERE date = ".$today." AND NOT EXISTS (SELECT ip FROM counter_user WHERE ip LIKE '".md5($_SESSION['current_ip'])."' and datum>=".$today.")");
		//aktualisiert den heutigen eintrag des users
		up("REPLACE INTO counter_user (ip, datum) VALUES ('".md5($_SESSION['current_ip'])."', ".time().")");
		//updatet die aufrufe oder erstellt die counter zeile für heute mit 1 besucher und 1 aufruf
		up("INSERT INTO counter (date, besucher, aufrufe) VALUES (".$today.",1,1) ON DUPLICATE KEY UPDATE aufrufe=aufrufe+1");

	//Read 
	$count_today = db("SELECT aufrufe, besucher FROM counter WHERE date=".$today,'object');
	$count_total = db("SELECT AVG(aufrufe) AS daily_avg,SUM(aufrufe) AS count_total, SUM(besucher) AS users_total FROM counter ",'object');

	//Output
	 return show("panels/counter", array(
                                    "count_today" => $count_today->aufrufe,
                                    "users_today" => $count_today->besucher,
                                    "users_total" => $count_total->users_total,
                                    "count_total" => $count_total->count_total,
                                    "daily_avg" => (int)$count_total->daily_avg));
}