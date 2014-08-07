<?php

class UserCounter extends MainModule
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