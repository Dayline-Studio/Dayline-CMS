<?php
if ($do == '') { 
    switch($action)
     {
         default:
             $disp = show('acp/acp_socialnetworks');
             break;
     }
} else {
    switch ($do)
    {
        case 'update':
            if (permTo('update_socialnetwork')) {
                if (up('Update settings SET ('
                        . 'link_twitter = '.sqlStringCon($_POST['twitter']).','
                        . 'link_facebook = '.sqlStringCon($_POST['facebook']).','
                        . 'link_google = '.sqlStringCon($_POST['google']).','
                        . 'link_youtube = '.sqlStringCon($_POST['youtube']).''
                        . ' LIMIT 1')) {
                    $disp = msg(_change_sucessful);
                } else {
                    $disp = msg(_change_failed);
                }    
            } else { 
                $disp = msg(_no_permissions);
            }
            break;
    }
}
 

