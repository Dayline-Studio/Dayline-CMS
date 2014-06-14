<?php

class Message {

    public  $content,
            $subject,
            $receiver_id,
            $sender_id,
            $date,
            $id;

    public function __construct($data) {
        foreach ($data as $varname => $content) {
            $this->$varname = $content;
        }
    }

    public function delete_inbox_sender() {
        Db::nrquery('UPDATE messages SET outbox = 0 WHERE id ='. $this->id);
    }

    public function delete_inbox_receiver() {
        Db::nrquery('UPDATE messages SET inbox = 0 WHERE id ='. $this->id);
    }

    public function get_message() {
        return get_object_vars(this);
    }
}