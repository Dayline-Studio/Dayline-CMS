<?php
// Site Informations
/**--**/
$meta['title'] = "User Verwalten";
//------------------------------------------------

if ($do == "") {
    switch ($action) {
        default:
            $users = Db::npquery('SELECT id,name FROM users');
            $userlist = "";
            foreach ($users as $user) {
                $userlist .= show('acp/acp_select_option',
                    array(
                        'value' => $user['id'],
                        'title' => $user['name']
                    ));
            }
            $disp = show('acp/acp_user',
                array(
                    'user_list' => $userlist
                ));
            break;
        case 'edit_group':
            $te = new TemplateEngine("acp/acp_groups_edit");
            $row['permission_list'] = "";
            $groups = Db::npquery('SELECT * FROM groups WHERE id = ' . $_GET['id'] . ' LIMIT 1');
            $case = array();
            foreach ($groups as $permission => $value) {
                if ($permission != 'id' && $permission != 'groupid') {
                    $case[] = array(
                        'permission_title' => '{s_' . strtoupper($permission) . '}',
                        'permission' => $permission,
                        'checked' => $value ? 'checked' : ''
                    );
                }
            }
            $te->addArr('permission_list', $case);
            $row['id'] = $_GET['id'];
            $row['group'] = $groups['groupid'];
            $disp = show($te->render(), $row);
            break;
        case 'group_list':
            $groups = Db::npquery('SELECT id,groupid FROM groups');
            $rows = "";
            foreach ($groups as $group) {
                $infos = "";
                foreach ($group as $value) {
                    $infos .= '<td>' . $value . "</td>";
                }
                $rows .= show('acp/acp_groups_tr',
                    array(
                        'infos' => $infos,
                        'id' => $group['id'],
                        'group_name' => $group['groupid']
                    ));
            }
            $disp = show('acp/acp_groups',
                array(
                    'rows' => $rows
                ));
            break;
    }
} else {
    switch ($do) {
        case 'update_group':
            if (permTo('edit_group')) {
                $groups = Db::query('SELECT * FROM groups WHERE id = :id LIMIT 1', array('id' => $_GET['id']));
                $form = new Form($_POST,array_keys(array_slice($groups, 2)));
                Db::update('groups',$_GET['id'],$form->get_vars_raw());
                goToWithMsg('back','Done');
            }
            break;
        case 'delete_group':
            if (permTo('delete_group')) {
                if (up('DELETE FROM groups WHERE id =' . $id)) {
                    $disp = msg(_change_successful);
                } else {
                    $disp = msg(_change_failed);
                }
            } else {
                $disp = msg(_no_permissions);
            }
            break;
    }
}
