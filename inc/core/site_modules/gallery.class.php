<?php

class Gallery extends MainModule
{

    public $subfrom = 0;

    public function render()
    {
        return '';
    }

    public function render_admin()
    {
        $te = new TemplateEngine('site/modules/gallery_admin');
        return $te->render();
    }
}