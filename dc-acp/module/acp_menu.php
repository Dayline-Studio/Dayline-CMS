<?php
//------------------------------------------------
// Site Informations
/**--**/
$meta['title'] = "Men&uuml; Konfigurieren";
//------------------------------------------------

function getMenuList()
{
    return Db::npquery("SELECT * FROM menu WHERE part = 0");
}

function getOptionList($list, $sel = 0)
{
    $ret = "";
    foreach ($list as $data) {
        $ocase['title'] = $data['title'];
        $ocase['value'] = $data['id'];
        if ($data['id'] == $sel) {
            $ret .= show('acp/acp_select_option_selected', $ocase);
        } else {
            $ret .= show('acp/acp_select_option', $ocase);
        }
    }
    return show('acp/acp_select_option', array('value' => 0, 'title' => "")) . $ret;
}

if ($do == "") {
    switch ($action) {
        default:
            $menu = getMenuList();
            $list = '';
            foreach ($menu as $data) {
                $newtab = '';
                if ($data['newtab']) {
                    $newtab = 'checked';
                }
                $list .= show("acp/acp_menu_list_tr", array('checked' => $newtab, 'id' => $data['id'], 'title' => $data['title'], 'options' => getOptionList($menu, $data['subfrom']), 'link' => $data['link']));
            }
            $add = show("acp/acp_menu_list_tr_new", array("title" => "", "link" => "", "options" => getOptionList($menu)));
            $disp = show("acp/acp_menu", array("list" => $list, "add" => $add));
            break;
    }
} else {
    switch ($do) {
        case 'create_menu':
            if (permTo('create_menu')) {
                if (isset($_POST['newtab'])) {
                    $newtab = $_POST['newtab'];
                } else {
                    $newtab = 0;
                }
                $up['title'] = $_POST['title'];
                $up['subfrom'] = $_POST['subfrom'];
                $up['link'] = $_POST['link'];
                $up['newtab'] = $newtab;
                $up['part'] = $_POST['part'];

                if (Db::insert('menu', $up)) {
                    __c("files")->delete("menu");
                    goBack();
                } else {
                    $disp = msg(_change_failed);
                }
            } else {
                $disp = msg(_no_permissions);
            }
            break;
        case 'delete_menu':
            if (permTo('delete_menu')) {
                Db::query('DELETE FROM menu WHERE id = :id', array('id' => $_GET['id']));
                    __c("files")->delete("menu");
                    goBack();
            } else {
                $disp = msg(_no_permissions);
            }
            break;
        case 'update_menu':
            if (permTo('update_menu')) {
                foreach ($_POST as $id => $data) {
                    if (!Db::update('menu', $id, array(
                        'subfrom' => $data['up_subfrom'],
                        'link' => $data['up_link'],
                        'title' => $data['up_title'],
                        'newtab' => isset($data['up_newtab']) ? 1 : 0
                    ))) {
                        $disp = msg(_update_failed);
                        break;
                    }
                }
                __c("files")->delete("menu");
                goBack();
            } else {
                $disp = msg(_no_permissions);
            }
            break;
    }
}