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
            $te = new TemplateEngine('acp/acp_news_create');
            $groups = Db::npquery("SELECT groupid, id FROM groups ORDER BY groupid DESC", PDO::FETCH_OBJ);
            foreach ($groups as $group) {
                    $op[] = array('value' => $group->id, 'title' => $group->groupid);
            } $te->add_var('groups',  get_options($op));
            $te->add_var('editor', get_editor());
            $disp = $te->render();
            break;
        case 'news_manage':
            News::init();
            $te = new TemplateEngine();
            foreach (News::$post as $post) {
                $news[] = array(
                    'id' => $post->id,
                    'title' => $post->title,
                    'edit_link' => '?acp=acp_news&action=post_edit&id='.$post->id,
                    'where' => $_GET['acp']
                );
            } $te->addArr('rows', $news);
            $te->setHtml("acp/acp_list");
            $disp = $te->render();
    }
} else {
    switch($do)
    {
        case 'create_post':
            if (permTo('create_news')) {
                if(News::createPost($_POST)) {
                        updateRSS();
                        goToWithMsg('?acp=acp_news&action=news_manage', 'Post added', 'success');
                     }
                else { $disp = msg(_change_failed); }
            } else { $disp = msg(_no_permissions); }
            break;
        case 'delete':

            if (permTo('delete_news')) {
                News::init();
                if(News::$post[$_GET['id']]->delete()) {
                    updateRSS();
                    goToWithMsg('back', 'Post deleted', 'warning');
                 } else {
                     $disp = msg(_change_failed);
                 }
            } else { $disp = msg(_no_permissions); }
            break;
    }
}