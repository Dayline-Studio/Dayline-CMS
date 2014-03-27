<?php
// Site Informations
/**--**/  $meta['title'] = "Gruppen";
//------------------------------------------------
  
if ($do == "")
{
    switch ($action)
    {
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
            $content = show('acp/acp_groups',
                    array(
                        'rows' => $rows
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
