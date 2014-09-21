<?php
class Form {

    private $fields;
    private $checkboxes;

    public function __construct($input = array(), $checkboxes = array()) {
        $this->checkboxes = $checkboxes;
        foreach ((array)$input as $key => $val) {
            $this->fields[$key] = $val;
        }
    }

    public function get_vars_raw() {
        foreach ($this->checkboxes as $key) {
            if (!array_key_exists($key,$this->fields)) {
                $this->fields[$key] = 0;
            }
        }
        return $this->fields;
    }

    public function get_vars_out() {
        return $this->convert_checkboxes($this->get_vars_raw());
    }

    private function convert_checkboxes($arr) {
        $arr = (array)$arr;
        foreach ($arr as $key => $val) {
            if ($val === '1') {
                $arr[$key] = 'checked';
            } else if ($val === '0') {
                $arr[$key] = '';
            } else {
                $arr[$key] = $val;
            }
        }
        return $arr;
    }

}