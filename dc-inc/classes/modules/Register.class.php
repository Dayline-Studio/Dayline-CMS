<?php

class Register extends ModuleModel
{

    public $register = [], $kind = 'vert_cookie_slider';

    protected function render()
    {
        $te = new TemplateEngine();
        switch ($this->kind) {
            case 'vert_cookie_slider':
                $te->setHtml('site/modules/register/vert_cookie_slider');
                break;
            case 'hor_simple':
                $te->setHtml('site/modules/register/hor_simple');
                break;
        }
        $te->addArr('register', $this->register);
        return ModuleController::scanModules($te->render());
    }

    protected function render_admin()
    {
        $te = new TemplateEngine('site/modules/register_admin');
        $reg_arr = [];
        for ($i = 1; $i < 5; $i++) {
            if (isset($this->register->$i)) {
                $reg_arr['title_'.($i)] = $this->register->$i->title;
            } else {
                $reg_arr['title_'.($i)] = '';
            }
        }
        $te->add_vars($reg_arr);
        return $te->render();
    }

    public function set_vars($data = array())
    {
        $i = 1;
        $n_reg = [];
        foreach ($data['register'] as $reg) {
            if (!empty($reg)) {
                $n_reg[$i] = array(
                    'title' => $reg,
                    'name' => 'reg_' . (String)$i,
                    'module_position' => '{position_register-' . $this->id . '-' . (String)$i . '}'
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