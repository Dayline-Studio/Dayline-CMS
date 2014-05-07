<?php
/**
 * Created by PhpStorm.
 * User: sasch_000
 * Date: 4/28/14
 * Time: 8:27 AM
 */

class Inbox {

    public static $inbox;
    public static $outbox;

    public static function init() {
        $messages = DB::npquery('SELECT * FROM messages WHERE receiver_id = '.$_SESSION['userid'].' AND sender_id = '.$_SESSION['userid']);
        foreach ($messages as $message){
            if ($message->receiver_id == $_SESSION['userid']) {
                self::$inbox[$message->id] = $message;
            } else {
                self::$outbox[$message->id] = $message;
            }
        }
    }

    public static function loadInbox() {

    }

    public static function loadOutbox() {

    }

} 