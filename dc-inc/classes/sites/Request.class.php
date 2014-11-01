<?php
class Request {

    private $data;

    public function __construct($request) {
        $this->data = $request;
    }

    public function get($name) {
        if (isset($data[$name])) {
            return $data[$name];
        } else {
            return NULL;
        }
    }
}