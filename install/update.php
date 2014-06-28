<?php
$current_version = 0.3; //database todo

$update = array();
$update[0.5] = 'ALTER TABLE `server_run`  ADD `test` INT(100) NOT NULL';


$config = @simplexml_load_file("config.xml");
echo $config->version;


if($config->version > $current_version) {
	install_update();
}
	
	
function install_update() {
		//todo funktion install
}




