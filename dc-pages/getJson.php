<?php
// Include CMS System
include "../dc-inc/base.php";
//------------------------------------------------

backSideFix();

$ret = NULL;

switch($_REQUEST['r']) {
    case 'pageInformations':
        $ret = new stdClass();
        $ret->settings = Config::$settings;
        $ret->path = Config::$path;
        break;
    case 'moduleInformations':
        $ids = $_REQUEST['module'];
        $ids = explode(',', $ids);
        $ret = [];
        foreach ($ids as $id) {
            $ret[] = get_module($id);
        }
        break;
}

echo json_encode($ret);

function get_module($id)
{
    $module_name = get_module_name($id);
    return new $module_name($id);
}