<?php

class User {

    public $id, $gplus, $name, $pass, $email, $rounds, $user, $street, $firstname, $lastname, $country, $groups;

    public function __construct($data) {
        if (is_array($data)) {
            $this->set_data($data);
        } else if (is_numeric($data)) {
            if ($data = Db::npquery("SELECT * FROM users WHERE id = $data", PDO::FETCH_ASSOC)) {
                echo 1;
                $this->set_data($data);
            }
        }

    }

    private function set_data($data) {
        foreach($data as $key => $value) {
            if (isset($this->$key)) {
                $this->$key = $value;
            }
        }
    }
}
