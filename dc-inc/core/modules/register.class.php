<?php

class Register extends MainModule
{

    public $register = [], $kind = 'vert_cookie_slider';

    protected function render()
    {
        $te = new TemplateEngine();
        switch($this->kind) {
            case 'vert_cookie_slider':
                $te->setHtml('site/modules/register/vert_cookie_slider');
                break;
        }
        $te->addArr('register', $this->register);
        return Disp::read_modules($te->render());
    }

    protected function render_admin()
    {
        $te = new TemplateEngine('site/modules/register_admin');
        $te->addArr('register', $this->register);
        return $te->render();
    }

    public function set_vars($data = array()) {
        $i = 1;
        foreach ($data['register'] as $reg) {
            if (!empty($reg)) {
                $n_reg[$i] = array(
                    'title' => $reg,
                    'name' => 'reg_'.(String)$i,
                    'module_position' => '{position_register-'.$this->id.'-'.(String)$i.'}'
                );
            }
            $i++;
        }
        $this->register = $n_reg;
        unset($data['register']);
        foreach ($data as $var => $value) {
            if (isset($this->$var)) {
                $this->$var = $value;
            }
        }
    }
}