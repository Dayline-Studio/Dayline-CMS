<?php

class Webspace extends Server {

    public function __construct($data) {
        parent::__construct($data);
        $this->functions = array(
            'change_password'
        );
        $this->htmlFile = 'site/modules/servermanager/show_webspace';
    }
    
    
    public function change_password() {
        $password = $_POST['ftp_password'];
        return $this->robotAddCall('pwchange'.'/'.$password);
    }

    public function dispModul1() {
        $disp = "";
        foreach ($this->functions as $function) {
            $case['id'] = $this->id;
            $case['action'] = $function;
            $case['value'] = con_to_lang("server_".$function);
            $disp .= show('servermanager/module/input_button', $case);
        }
        return $disp;
    }

    public function load_informations() {
        $this->php_version = phpversion();
        $this->openssl_version = OPENSSL_VERSION_TEXT;
        $this->a2_version = apache_get_version();
    }
}