<?php
// Site Informations
/**--**/  $meta['title'] = "User Verwalten";
//------------------------------------------------
  
if ($do == "")
{
    switch ($action)
    {
        default:
            $users = db('SELECT id,name FROM users');
            $userlist = "";
            while ($user = _assoc($users)) {
                $userlist .= show('acp/acp_select_option',
                        array(
                            'value' => $user['id'],
                            'title' => $user['name']
                        ));
            }
            $content = show('acp/acp_user',
                    array(
                        'user_list' => $userlist
                ));
            break;
    }
} else {
    switch ($do)
    {
        case 'update_settings':

            break;
    }
}
