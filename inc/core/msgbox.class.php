<?php
/**
 * Created by PhpStorm.
 * User: sasch_000
 * Date: 4/28/14
 * Time: 8:27 AM
 */

class Msgbox {

    public $inbox;
    public $outbox;

    public function __construct($userid) {
        $messages = DB::npquery(
            "SELECT
                m.id,
                sender_id,
                receiver_id,
                opened,
                outbox,
                inbox,
                m.date,
                title,
                content,
                r.name receiver_name,
                r.email receiver_email,
                s.name sender_name,
                s.email sender_email
            FROM messages m
            RIGHT JOIN users r ON (m.receiver_id = r.id)
            RIGHT JOIN users s ON (m.sender_id = s.id)
            WHERE receiver_id = $userid AND sender_id = $userid",PDO::FETCH_OBJ);
        foreach ($messages as $message){
            if ($message->receiver_id == $userid) {
                $this->inbox[$message->id] = new Message($message);
            } else {
                $this->outbox[$message->id] = new Message($message);
            }
        }
    }
} 