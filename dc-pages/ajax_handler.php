<?php
// Include CMS System
include "../dc-inc/base.php";
//------------------------------------------------

if (isset($_REQUEST['refresh'])) {
    die();
}

Auth::backSideFix();

foreach ($_REQUEST as $key => $value) {
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
        case 'module_task':
            $module_task = $value;
            break;
        default:
            $vars[$key] = $value;
    }
}

switch ($action) {
    case 'update':
        if (permTo('site_edit')) update_module($id, $vars);
        break;
    case 'delete':
        if (permTo('site_edit')) delete_module($id);
        break;
    case 'create':
        if (permTo('site_edit')) create_module($module, $vars);
        break;
    case 'move_up':
        if (permTo('site_edit')) move('up', $id);
        break;
    case 'move_down':
        if (permTo('site_edit')) move('down', $id);
        break;
    case 'reload_module':
        reload_module($id);
        break;
    case 'get_post_result_render':
        get_post_result_render($id);
        break;
}


function delete_module($id)
{
    get_module($id)->delete();
    echo 1;
}

function update_module($id, $up)
{
    $module_obj = get_module($id);
    $module_obj->set_vars($up);
    $module_obj->update();
    echo $module_obj->get_render();
}

function reload_module($id)
{
    echo get_module($id)->json_render();
}

function create_module($module, $vars)
{
    $module = new $module($vars['position'], true);
    echo $module->json_render();
}

function move($dir, $id)
{
    echo get_module($id)->move($dir);
}

function get_post_result_render($id)
{
    echo get_module($id)->json_render();
}

function send_task($id)
{
    echo get_module($id)->render();
}

function get_module($id)
{
    $module_name = get_module_name($id);
    return new $module_name($id);
}