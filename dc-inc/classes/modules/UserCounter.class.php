<?php

class UserCounter extends ModuleModel
{

    protected function render()
    {
        return '{dyn_counter}';
    }

    protected function render_admin()
    {
        return "";
    }
}