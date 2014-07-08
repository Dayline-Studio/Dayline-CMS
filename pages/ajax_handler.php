<?php
// Include CMS System
include "../inc/base.php";
//------------------------------------------------

// url: ../pages/ajax_handler.php?module=editabletext&id=1&do=update&site_id=4

if ($_SESSION['userid'] == 1) {
    foreach ($_POST as $key => $value) {
        switch ($key) {
            case 'module':
                $module = $value;
                break;
            case 'id':
                $id = $value;
                break;
            case 'action':
                $action = $value;
                break;
            default:
                $vars[$key] = $value;
        }
    }

    switch ($action) {
        case 'update':
            update_module($module, $id, $vars);
            break;
        case 'delete':
            delete_module($module);
            break;
        case 'create':
            create_module($module, $vars);
            break;
        case 'move_up':
            move('up',$id);
            break;
        case 'move_down':
            move('down',$id);
            break;
    }
}

function get_module_name($id)
{
    $mod = Db::npquery("SELECT module FROM modules WHERE id = $id", PDO::FETCH_OBJ);
    return $mod[0]->module;
}

function delete_module($id)
{
    $module_name = get_module_name($id);
    $module_obj = new $module_name($id);
    $module_obj->delete();
    echo 1;
}

function update_module($module, $id, $up)
{
    $module_obj = new $module($id);
    $module_obj->set_vars($up);
    $module_obj->update();
    echo $module_obj->render();
}

function create_module($module, $vars)
{
    $module = new $module($vars['position'], true);
    echo $module->full_render();
}

function move($dir, $id) {
    $module_name = get_module_name($id);
    $module_obj = new $module_name($id);
    echo $module_obj->move($dir);
}