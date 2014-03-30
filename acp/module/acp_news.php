<?php
// Site Informations
/**--**/  $meta['title'] = "News Eintragen";
//------------------------------------------------
// Site Permissions
/**--**/ if (!permTo("create_news")) { $error = msg(_no_permissions); }
//------------------------------------------------

$subsite[0] = "news_create";
$subsite[1] = "news_manage";

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
            $content = show("acp/acp_news_create",array("options_group" => $options));
            break;
        case 'news_manage':
            $news = db('SELECT title,id FROM news');
            $options = "";
            while ($post = _assoc($news)) {
                $options .= show('acp/acp_select_option',
                        array(
                            'value' => $post['id'],
                            'title' => $post['title']
                        ));
            }
            $content = show("acp/acp_news_manage", array('options' => $options));
            break;
    }
} else {
    switch($do)
    {
        case 'create_news':
            if (permTo('create_news')) {
                if(up("INSERT INTO news ("
                        . "id, content, title, date, "
                        . "grp, public_show, description, "
                        . "main_image, userid) VALUES ("
                        . "NULL, "
                        .sqlString($_POST['mce_0']).", "
                        .sqlString($_POST['title']).", "
                        .sqlInt(time()).", "
                        .sqlInt($_POST['keywords']).", "
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
        case 'delete_news':
            if (permTo('delete_news')) {
                if(up("DELETE FROM news WHERE id = ".sqlInt($_POST['id']))) {
                    $content = msg(_change_sucessful);
                    updateRSS();
                 } else {
                     $content = msg(_change_failed);
                 }
            } else { $content = msg(_no_permissions); }
            break;
    }
}