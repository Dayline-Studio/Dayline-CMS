<?php
// Site Informations
/**--**/  $meta['title'] = _acp_groups;
//------------------------------------------------

if ($do == "")
{
    switch ($action)
    {	
		case 'edit_group':
			$groups = db('SELECT * FROM groups WHERE id ='.sqlInt($_GET['id']),'array');
			foreach($groups as $permission => $value){
			}
			
		break;
		
		default:
            $groups = db('SELECT id,groupid FROM groups');
            $rows = ""; 
            while ($group = _assoc($groups))
            {
                $infos = "";
                foreach ($group as $value) {
                    $infos .= "<td>".$value."</td>";
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
    switch ($do)
    {
		case 'delete_group':
			if (permTo('delete_group')){
				if (up('DELETE FROM groups WHERE id ='.sqlInt($_GET['id']))) {
				$disp = msg(_change_successful);
			} else {
				$disp = msg(_change_failed);
			}
			} else {
				$disp = msg(_no_permissions);
			}
			break;
        case 'update_group':
			//hier kommt noch was
            break;
    }
}
