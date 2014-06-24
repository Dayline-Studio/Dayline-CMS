<?php

class EditableText extends MainModule
{

    public $content = '';

    public function render()
    {
        $file = 'site/modules/editabletext_show';
        return show($file, array('content' => $this->content));
    }

    public function render_admin()
    {
        $file = 'site/modules/editabletext_admin';
        return show($file, array('content' => $this->content, 'id' => $this->id));
    }
}