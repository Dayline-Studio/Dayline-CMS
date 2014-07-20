<?php

class Disqus extends MainModule
{

    protected $max_count = 1;
    public $disqus_link = "";

    protected function render()
    {
        if (empty($this->disqus_link)) {
            return "Insert your Disqus link first";
        }
        $file = 'site/modules/disqus_show';
        return show($file, get_object_vars($this));
    }

    protected function render_admin()
    {
        $file = 'site/modules/disqus_admin';
        return show($file, get_object_vars($this));
    }
}