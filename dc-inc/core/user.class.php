<?php

class User
{
    public $id, $gplus, $name, $pass, $email, $street, $firstname, $lastname, $country, $main_group;

    public function __construct($data)
    {
        if (is_array($data)) {
            $this->set_data($data);
        } else if (is_numeric($data)) {
            if ($data = Db::query("SELECT * FROM users WHERE id = :id LIMIT 1",array('id' => $data), PDO::FETCH_OBJ)) {
                $this->set_data($data);
            }
        } else if(is_string($data)) {
            if ($data = Db::query("SELECT * FROM users WHERE lower(name) LIKE :query OR email LIKE :query LIMIT 1",array('query' => strtolower($data)), PDO::FETCH_OBJ)) {
                $this->set_data($data);
            }
        } else unset($this);
    }

    public function set_new_password($pw) {
        $this->pass = customHasher($pw);
    }

    protected function set_data($data)
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function update_changes() {
        Db::update('users',$this->id,get_object_vars($this));
    }
}
