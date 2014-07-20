<?php

class ContactForm extends MainModule
{
    public $target_users = array();
    private $available_tasks = array('send_message'), $max_count = 1;

    protected function render()
    {
        Debug::log('render');
        if (isset($_POST['module_task'])) {
            $task = $_POST['module_task'];
            if (in_array($task, $this->available_tasks)) {
                return $this->$task();
            }
        } else {
            $file = 'site/modules/contactform_show';
            return show($file);
        }
    }

    protected function render_admin()
    {
        $file = 'site/modules/contactform_admin';
        return show($file);
    }

    private function send_message()
    {
        if ($_POST['email'] == "" ||
            $_POST['subject'] == "" ||
            $_POST['message'] == ""
        ) {
            return _fields_missing;
        } else if (!check_email_address($_POST['email'])) {
            return _mailcheck_failed;
        } else {
            if (sendMessage(0, 1, $_POST['message'], 'Kontaktformular: ' . $_POST['subject'], $_POST['email'], true)) {
                return '<div class="alert alert-success" role="alert">' . _msg_sent_successful . '</div>';
            } else {
                return '<div class="alert alert-danger" role="alert">' . _msg_sent_failed . '</div>';
            }
        }
    }
}