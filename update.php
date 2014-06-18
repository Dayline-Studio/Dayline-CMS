<?php

$sql_up = array();
$sql_up[0.3][] = "ALTER TABLE settings CHANGE version version VARCHAR( 20 ) CHARACTER SET utf8 COLLATE utf8_bin NOT NULL";
$sql_up[0.3][] = "ALTER TABLE sites ADD show_socialbar INT( 1 ) NOT NULL DEFAULT '0'";
$sql_up[0.3][] = "ALTER TABLE groups ADD fm_access INT( 1 ) NOT NULL DEFAULT '0'";
$sql_up[0.3][] = "UPDATE groups SET fm_access = '1' WHERE groups.id = 1 LIMIT 1 ";
$sql_up[0.3][] = "UPDATE settings SET version = '0.3' WHERE settings.id =1 LIMIT 1 ";

foreach ($sql_up as $ver => $update) {
    if($ver > Config::$settings->version) {
        Db::nrquery($update);
    }
}