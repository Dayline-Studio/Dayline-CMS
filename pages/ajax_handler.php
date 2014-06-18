<?php
// Include CMS System
/**--**/ include "../inc/base.php";
//------------------------------------------------

// url: ../pages/ajax_handler.php?module=editabletext&id=1&do=update&site_id=4

if ($_SESSION['userid'] == 1) {

    $module = "";
    foreach ($_POST as $key => $value) {
        switch ($key) {
            case 'module':
                $module = $value;
                break;
            case 'id':
                $id = $value;
                break;
            case 'site_id':
                $site_id = $value;
                break;
            default:
                $up[$key] = $value;
        }
    }

    $module_obj = new $module($id);
    $module_obj->set_vars($up);
    echo $module_obj->render();
}