<?php

class Notification
{

    public $note;
    private $msg;
    private $type;

    public function __construct($msg, $type)
    {
        $this->set($msg, $type);
    }

    public function set($msg, $type)
    {
        $this->msg = $msg;
        $this->type = $type;
        $this->note = show(
            'allround/notification',
            array(
                'msg' => $this->msg,
                'type' => $this->type
            )
        );
        $this->set_session();
    }

    private function set_session()
    {
        $_SESSION['simple_note'] = $this->note;
    }
}