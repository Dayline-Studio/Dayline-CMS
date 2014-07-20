<?php
$sql_up = array();
$sql_up[300][] = "ALTER TABLE settings CHANGE version version VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL";
$sql_up[300][] = "ALTER TABLE sites ADD show_socialbar INT( 1 ) NOT NULL DEFAULT '0'";
$sql_up[300][] = "ALTER TABLE groups ADD fm_access INT( 1 ) NOT NULL DEFAULT '0'";
$sql_up[300][] = "UPDATE groups SET fm_access = '1' WHERE groups.id = 1 LIMIT 1 ";

$sql_up[303][] = "ALTER TABLE `modules` CHANGE `params` `params` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_bin NOT NULL ";

$sql_up[304][] = "ALTER TABLE `settings` ADD `construction_mode` INT( 1 ) NOT NULL DEFAULT '0'";
$sql_up[305][] = "ALTER TABLE `menu` CHANGE `link` `link` VARCHAR( 500 ) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL ";
$sql_up[305][] = "UPDATE `menu` SET `link` = '../dc-acp/' WHERE title LIKE 'ACP' LIMIT 1 ";
