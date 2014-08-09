<?php

class EditableText extends MainModule
{

    public $content = '';

    protected function render()
    {
        $file = 'site/modules/editabletext_show';
        return show($file, array('content' => Disp::read_modules($this->content)));
    }

    protected function render_admin()
    {
        $file = 'site/modules/editabletext_admin';
        $content = $this->content;
        if (empty($this->content)) {
            $content = _enter_content_here;
        }
        return show($file, array('content' => $content, 'id' => $this->id));
    }
}