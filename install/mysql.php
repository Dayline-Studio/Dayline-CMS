<?php

$tables = array();
					
$tables['categories']= "
					`id` int(10) NOT NULL AUTO_INCREMENT,
					`title` varchar(200) CHARACTER SET latin1 NOT NULL,
					`tags` varchar(400) CHARACTER SET latin1 NOT NULL,
					`image` varchar(400) CHARACTER SET latin1 NOT NULL,
					PRIMARY KEY (`id`)";
					  
$tables['comments'] = "
					`id` int(10) NOT NULL AUTO_INCREMENT,
					`userid` int(10) NOT NULL,
					`date` int(10) NOT NULL,
					`content` text CHARACTER SET latin1 NOT NULL,
					`site` int(10) NOT NULL,
					`subsite` int(10) NOT NULL,
					`active` int(1) NOT NULL,
					PRIMARY KEY (`id`)";
					
$tables['counter'] = "	
					`date` int(100) NOT NULL AUTO_INCREMENT,
					`besucher` int(100) DEFAULT NULL,
					`aufrufe` int(100) DEFAULT NULL,
					PRIMARY KEY (`date`)";				
					
$tables['counter_user'] = "					
					`ip` varchar(200) CHARACTER SET latin1 NOT NULL,
					`datum` int(100) NOT NULL,
					PRIMARY KEY (`ip`)";

$tables['gallery'] = "
					`id` int(100) NOT NULL AUTO_INCREMENT,
					`title` text COLLATE utf8_bin NOT NULL,
					`path` varchar(500) COLLATE utf8_bin DEFAULT NULL,
					`subfrom` int(100) NOT NULL,
					`position` int(100) NOT NULL,
					PRIMARY KEY (`id`)";					

$tables['gameserver'] = "
					  `id` int(5) NOT NULL AUTO_INCREMENT,
					  `ip` varchar(32) CHARACTER SET latin1 NOT NULL,
					  `port` int(10) NOT NULL,
					  `type` varchar(10) CHARACTER SET latin1 NOT NULL,
					  PRIMARY KEY (`id`)";					
					
$tables['groups'] = "
					  `id` int(5) NOT NULL AUTO_INCREMENT,
					  `groupid` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `site_edit` int(1) NOT NULL DEFAULT '0',
					  `create_site` int(1) NOT NULL,
					  `menu_acp` int(1) NOT NULL,
					  `comment` int(1) NOT NULL,
					  `create_news` int(1) NOT NULL,
					  `delete_site` int(1) NOT NULL,
					  `reset_counter` int(1) NOT NULL,
					  `delete_news` int(1) NOT NULL,
					  `update_socialnetwork` int(1) NOT NULL,
					  `mail_abo` int(1) NOT NULL,
					  `msg_send` int(1) NOT NULL,
					  `delete_group` int(1) NOT NULL,
					  `create_menu` int(1) NOT NULL,
					  `delete_menu` int(1) NOT NULL,
					  `update_menu` int(1) NOT NULL,
					  `fm_access` int(1) NOT NULL,
					  PRIMARY KEY (`id`)";					
					
$tables['menu'] = "
					  `id` int(5) NOT NULL AUTO_INCREMENT,
					  `title` varchar(25) COLLATE utf8_bin NOT NULL,
					  `subfrom` int(5) NOT NULL,
					  `link` varchar(40) CHARACTER SET latin1 NOT NULL,
					  `newtab` int(1) NOT NULL,
					  `part` int(5) NOT NULL,
					  `position` int(5) NOT NULL,
					  PRIMARY KEY (`id`)";
										
$tables['messages'] = "
					  `id` int(10) NOT NULL AUTO_INCREMENT,
					  `sender_id` int(10) NOT NULL,
					  `receiver_id` int(10) NOT NULL,
					  `opened` int(1) NOT NULL,
					  `outbox` int(1) NOT NULL,
					  `inbox` int(1) NOT NULL,
					  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
					  `date` int(10) NOT NULL,
					  `title` varchar(300) CHARACTER SET latin1 NOT NULL,
					  `content` text CHARACTER SET latin1 NOT NULL,
					  PRIMARY KEY (`id`)";											
					
$tables['modules'] = "
					  `id` int(11) NOT NULL AUTO_INCREMENT,
					  `module` varchar(300) COLLATE utf8_bin NOT NULL,
					  `params` text COLLATE utf8_bin NOT NULL,
					  `position` varchar(300) COLLATE utf8_bin NOT NULL,
					  PRIMARY KEY (`id`)";					
					
$tables['news'] = "
					  `id` int(5) NOT NULL AUTO_INCREMENT,
					  `content` text CHARACTER SET latin1 NOT NULL,
					  `title` varchar(200) CHARACTER SET latin1 NOT NULL,
					  `date` int(20) NOT NULL,
					  `grp` int(5) NOT NULL,
					  `public_show` int(1) NOT NULL,
					  `description` varchar(500) CHARACTER SET latin1 NOT NULL,
					  `main_image` varchar(200) CHARACTER SET latin1 NOT NULL,
					  `userid` int(10) NOT NULL,
					  `keywords` varchar(400) CHARACTER SET latin1 NOT NULL,
					  PRIMARY KEY (`id`)";					
					  
$tables['pages'] = "
					  `id` int(10) NOT NULL AUTO_INCREMENT,
					  `title` varchar(200) CHARACTER SET latin1 NOT NULL,
					  PRIMARY KEY (`id`)";					  
					
$tables['server'] = "
					  `server_id` int(11) NOT NULL AUTO_INCREMENT,
					  `server_port` int(5) NOT NULL,
					  `server_query_port` int(5) NOT NULL,
					  `server_interface` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `server_name` varchar(50) CHARACTER SET latin1 NOT NULL,
					  `server_owner` int(10) NOT NULL,
					  `server_kind` varchar(50) CHARACTER SET latin1 NOT NULL,
					  `server_type` varchar(50) CHARACTER SET latin1 NOT NULL,
					  `server_ip` varchar(25) CHARACTER SET latin1 NOT NULL,
					  PRIMARY KEY (`server_id`)";					
					
$tables['server_run'] = "
					  `run_command` varchar(200) CHARACTER SET latin1 NOT NULL,
					  `run_id` int(200) NOT NULL AUTO_INCREMENT,
					  `run_host` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `run_user` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `run_kind` varchar(10) CHARACTER SET latin1 NOT NULL,
					  `email` varchar(300) COLLATE utf8_bin NOT NULL,
					  `test` int(100) NOT NULL,
					  PRIMARY KEY (`run_id`)";					
					
$tables['settings'] = "
					  `id` int(5) NOT NULL AUTO_INCREMENT,
					  `website_title` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `publisher` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `copyright` varchar(256) CHARACTER SET latin1 NOT NULL,
					  `language` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `style` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `link_facebook` varchar(300) CHARACTER SET latin1 NOT NULL,
					  `link_google` varchar(300) CHARACTER SET latin1 NOT NULL,
					  `link_youtube` varchar(300) CHARACTER SET latin1 NOT NULL,
					  `link_twitter` varchar(300) CHARACTER SET latin1 NOT NULL,
					  `google_analytics` varchar(50) CHARACTER SET latin1 NOT NULL,
					  `force_domain` int(1) NOT NULL,
					  `domain` varchar(20) COLLATE utf8_bin NOT NULL,
					  `force_https` int(1) NOT NULL,
					  `version` varchar(20) COLLATE utf8_bin NOT NULL,
					  PRIMARY KEY (`id`)";					
					
$tables['sites'] = "
					  `id` int(5) NOT NULL AUTO_INCREMENT,
					  `title` text CHARACTER SET latin1 NOT NULL,
					  `userid` int(10) NOT NULL,
					  `keywords` varchar(256) CHARACTER SET latin1 NOT NULL DEFAULT ' ',
					  `description` varchar(256) CHARACTER SET latin1 NOT NULL DEFAULT ' ',
					  `subfrom` int(5) NOT NULL,
					  `position` int(5) NOT NULL,
					  `lastedit` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
					  `editby` varchar(25) CHARACTER SET latin1 DEFAULT NULL,
					  `date` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `show_lastedit` int(1) NOT NULL DEFAULT '0',
					  `show_author` int(1) NOT NULL DEFAULT '0',
					  `show_print` int(1) NOT NULL DEFAULT '0',
					  `show_headline` int(1) NOT NULL DEFAULT '0',
					  `show_socialbar` int(1) NOT NULL DEFAULT '0',
					  `public` int(1) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)";					
					
$tables['subscribe'] = "					
					  `id` int(10) NOT NULL AUTO_INCREMENT,
					  `email` varchar(200) CHARACTER SET latin1 NOT NULL,
					  PRIMARY KEY (`id`)";
					  
$tables['users'] = "
					  `id` int(5) NOT NULL AUTO_INCREMENT,
					  `gplus` varchar(24) CHARACTER SET latin1 NOT NULL,
					  `name` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `pass` varchar(100) CHARACTER SET latin1 NOT NULL,
					  `email` varchar(100) CHARACTER SET latin1 NOT NULL,
					  `rounds` int(10) NOT NULL,
					  `user` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `street` varchar(25) CHARACTER SET latin1 NOT NULL DEFAULT '',
					  `firstname` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `lastname` varchar(25) CHARACTER SET latin1 NOT NULL,
					  `country` varchar(25) CHARACTER SET latin1 NOT NULL DEFAULT '',
					  `main_group` int(5) NOT NULL DEFAULT '0',
					  PRIMARY KEY (`id`)";					  
					  
					  
					
					
					
					
					
					
					
					
					//Update
$update[0.2]['users'] = ""; 
