<?php

class StaticModuleClone extends MainModule
{

    public $module_id = '';
    public $module_class = '';

    public function render()
    {
        $module = new $this->$module_class($this->module_id);
        return $module->render();
    }

    public function render_admin()
    {
        $file = 'site/modules/editabletext_admin';
        return show($file, array('content' => $this->content, 'id' => $this->id));
    }
}