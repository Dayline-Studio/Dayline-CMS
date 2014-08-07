<?php

class NewsTicker extends MainModule
{

    public $y_size = 200, $zoom = 14, $query = "Deutschland";

    protected function render()
    {
        News::init();
        $te = new TemplateEngine();
        $te->setHtml(show('news/post'));
        $te->addArr('posts', News::get_news_from_group(2));
        return $te->render();
    }

    protected function render_admin()
    {

    }
}