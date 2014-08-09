<?php

class Ucp extends MainModule
{
    protected function render()
    {
        $title = 'UCP-Panel';
        if (!$_SESSION['loggedin']) {
            $login_panel = show("panels/login");
        } else {
            $gravatar = get_gravatar($_SESSION['email'], 100, false);
            $login_panel = show("panels/ucp_main",
                array(
                    "clock" => show("panels/clock"),
                    "new" => Db::npquery('SELECT count(id) as msgs FROM messages WHERE receiver_id = ' . $_SESSION['userid'] . ' AND opened = 0 AND inbox = 1 LIMIT 1', PDO::FETCH_OBJ)->msgs,
                    "user" => $_SESSION['name'],
                    "Gravatar" => $gravatar
                ));
        }
        return $login_panel;
    }

    protected function render_admin()
    {

    }
}