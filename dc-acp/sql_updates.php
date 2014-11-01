<?php
$sql_up = array();
$sql_up[300][] = "ALTER TABLE settings CHANGE version version VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL";
$sql_up[300][] = "ALTER TABLE sites ADD show_socialbar INT( 1 ) NOT NULL DEFAULT '0'";
$sql_up[300][] = "ALTER TABLE groups ADD fm_access INT( 1 ) NOT NULL DEFAULT '0'";
$sql_up[300][] = "UPDATE groups SET fm_access = '1' WHERE groups.id = 1 LIMIT 1 ";

$sql_up[303][] = "ALTER TABLE `modules` CHANGE `params` `params` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ";

$sql_up[304][] = "ALTER TABLE `settings` ADD `construction_mode` INT( 1 ) NOT NULL DEFAULT '0'";
$sql_up[305][] = "ALTER TABLE `menu` CHANGE `link` `link` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ";
$sql_up[305][] = "UPDATE `menu` SET `link` = '../dc-acp/admin' WHERE title LIKE 'ACP' LIMIT 1 ";

$sql_up[306][] = "ALTER TABLE `settings` ADD `home` VARCHAR( 500 ) NOT NULL DEFAULT '/news'";

$sql_up[308][] = "ALTER TABLE `groups` ADD `edit_settings` INT( 1 ) NOT NULL DEFAULT '0'";
$sql_up[308][] = "ALTER TABLE `settings` DROP `publisher` ,
                    DROP `copyright` ,
                    DROP `link_facebook` ,
                    DROP `link_google` ,
                    DROP `link_youtube` ,
                    DROP `link_twitter` ;";

$sql_up[309][] = "ALTER TABLE `groups` ADD `edit_group` INT( 1 ) NOT NULL DEFAULT '0'";

$sql_up[311][] = "DROP TABLE `news`, `subscribe`, `comments`";

$sql_up[312][] = "ALTER TABLE `settings` ADD `use_site_id` INT( 1 ) NOT NULL DEFAULT '0'";

$sql_up[313][] = "ALTER TABLE `sites` DROP `show_socialbar`";
$sql_up[313][] = "ALTER TABLE `sites` ADD `group` INT( 10 ) NOT NULL DEFAULT '0'";

$sql_up[314][] = "ALTER TABLE `users` DROP `user`";

$sql_up[315][] = "ALTER TABLE `settings` CHANGE `domain` `domain` VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ";
$sql_up[315][] = "ALTER TABLE `settings` CHANGE `website_title` `website_title` VARCHAR( 256 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ";

$sql_up[330][] = "ALTER TABLE `groups` DROP `comment`;";
$sql_up[330][] = "ALTER TABLE `groups` DROP `create_news`;";
$sql_up[330][] = "ALTER TABLE `groups` DROP `update_socialnetwork`;";
$sql_up[330][] = "ALTER TABLE `groups` DROP `mail_abo`;";
$sql_up[330][] = "ALTER TABLE `groups` DROP `delete_news`;";
$sql_up[330][] = "ALTER TABLE `groups` DROP `reset_counter`;";
$sql_up[330][] = "ALTER TABLE `groups` CHANGE `create_site` `create_site` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `groups` CHANGE `menu_acp` `menu_acp` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `groups` CHANGE `delete_site` `delete_site` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `groups` CHANGE `msg_send` `msg_send` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `groups` CHANGE `delete_group` `delete_group` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `groups` CHANGE `create_menu` `create_menu` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `groups` CHANGE `delete_menu` `delete_menu` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `groups` CHANGE `update_menu` `update_menu` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `groups` CHANGE `fm_access` `fm_access` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `groups` CHANGE `edit_settings` `edit_settings` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `groups` CHANGE `edit_group` `edit_group` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `users` CHANGE `main_group` `main_group` SMALLINT( 5 ) NOT NULL DEFAULT '0';
ALTER TABLE `sites` CHANGE `show_lastedit` `show_lastedit` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `sites` CHANGE `show_author` `show_author` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `sites` CHANGE `show_print` `show_print` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `sites` CHANGE `show_headline` `show_headline` TINYINT( 1 ) NOT NULL DEFAULT '0';
ALTER TABLE `sites` CHANGE `public` `public` TINYINT( 1 ) NOT NULL DEFAULT '0';";