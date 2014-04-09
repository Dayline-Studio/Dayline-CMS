<?php

$tables = array();
$tables['categories'] = "	
					id INT( 10 ) NOT NULL AUTO_INCREMENT, 
					name VARCHAR ( 200 ),
					tags VARCHAR ( 400 ),
					image VARCHAR ( 400 ),
					PRIMARY KEY (id)";
					
$tables['comments'] = "
					id INT ( 10 ) NOT NULL AUTO_INCREMENT,
					title VARCHAR ( 200 ),
					tags VARCHAR ( 400 ),
					image VARCHAR ( 400 ),
					PRIMARY KEY (id)";
		
$tables['counter'] = "
					date INT ( 100 ) NOT NULL AUTO_INCREMENT,
					besucher INT ( 100 ),
					aufrufe INT ( 100 ),
					PRIMARY KEY (date)";
					
