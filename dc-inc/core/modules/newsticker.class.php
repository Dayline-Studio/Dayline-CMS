<?php

class NewsTicker extends MainModule
{
    protected function render()
    {
        $posts = [];
        $modules = Db::npquery("SELECT * FROM modules WHERE module LIKE 'NewsPost'",PDO::FETCH_OBJ);
        foreach ($modules as $module) {
            $posts[] = new NewsPost($module->id);
        }
        $post_info = [];
        foreach($posts as $post) {
            $post_info[] = array(
                'title' => $post->get_title(),
                'content' => $post->get_content(),
                'url' => $post->get_url(),
                'date_out' => $post->get_date(true)
            );
        }

        $te = new TemplateEngine('news/post');
        $te->addArr('posts', $post_info);
        return $te->render();
    }

    protected function render_admin()
    {

    }
}