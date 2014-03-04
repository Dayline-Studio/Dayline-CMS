<?php
class ErrorHandler {
 
    private $errors;
    
    public function addError($error)
    {
        $this->errors .= $error."<br>";
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}
