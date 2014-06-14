<?php

$sql_up = array();
$sql_up[0.3] = "";

foreach ($sql_up as $ver => $update) {
    if($ver > Config::$settings->version) {
        Db::nrquery($update);
    }
}