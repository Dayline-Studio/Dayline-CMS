<?php
    // Site Informations
    /**--**/  $meta['title'] = "News Eintragen";
    //------------------------------------------------
    // Site Permissions
    /**--**/ if (!permTo("create_news")) { $error = msg(_no_permissions); }
    //------------------------------------------------
        
    if ($do == "")
    {
            switch($action)
            {
                    default:
                            $qry = db("SELECT groupid, id FROM groups ORDER BY groupid DESC");
                            $options = "";
                            while($group = mysqli_fetch_assoc($qry))
                            {
                                    $options .= '<option value="'.$group['id'].'">'.$group['groupid'].'</option>';
                            }
                            $content = show("acp/acp_news",array("options_group" => $options));
                            break;
            }
    }
    switch($do)
    {
            case create_news:
                if (permTo('create_news')) {
                    if(up("INSERT INTO news ("
                            . "id, content, title, date, "
                            . "grp, public_show, description, "
                            . "main_image, userid) VALUES ("
                            . "NULL, "
                            .sqlString($_POST['mce_0']).", "
                            .sqlString($_POST['title']).", "
                            .sqlInt(time()).", "
                            .sqlInt($_POST['groupid']).", "
                            .sqlString($_POST['visible']).", "
                            .sqlString($_POST['description']).", "
                            .sqlString($_POST['main_image']).", "
                            .sqlInt($_SESSION['userid'])
                            . ")"
                       )){
                            updateRSS();
                            $content = msg(_entry_successful);
                         }
                    else { $content = msg(_change_failed); }
                } else { $content = msg(_no_permissions); }
                break;
    }