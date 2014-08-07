<?php

class NewsPost extends MainModule
{
    public $site_id;

    protected function on__create() {
        $this->site_id = explode('-',$this->position)[2];
    }

    protected function render()
    {
        return '';
    }

    protected function render_admin()
    {
        return '';
    }
}