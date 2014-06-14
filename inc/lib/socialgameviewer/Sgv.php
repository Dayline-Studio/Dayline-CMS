<?php

function sgv_set_language($lang) {
    global $sgv;
	$sgv['lang'] = $lang;
}

function sgv_enable_phpfastcache($bl, $class_path = "") {
    global $sgv;
	$sgv['phpfastcache'] = $bl;
	$sgv['phpfastcache_path'] = $class_path;
}

function sgv_set_max_disp($ct) {
    global $sgv;
	$sgv['max_disp'] = $ct;
}

function sgv_set_steam_api_key($key) {
    global $sgv;
	$sgv['steam_api_key'] = $key;
}

function sgv_show_offline($b) {
    global $sgv;
	$sgv['view_offline'] = $b;
}

function sgv_show_addfriend($b) {
    global $sgv;
	$sgv['view_addfriend'] = $b;
}

function sgv_show_private($b) {
    global $sgv;
    $sgv['view_privat'] = $b;
}

function sgv_show_newtab($b) {
    global $sgv;
	$sgv['view_newtab'] = $b;
}

function sgv_set_x_size($i) {
    global $sgv;
    $sgv['x_size'] = $i;
}

function sgv_set_output_modul($modul) {
    global $sgv;
    $sgv['modul'] = $modul;
}

// Classes
$dir = dirname(__DIR__).'/Socialgameviewer/';
include $dir . 'core/sgv.php';
include $dir . 'core/sgv.dzcp.php';
include $dir . 'core/sgv.steam_group.php';