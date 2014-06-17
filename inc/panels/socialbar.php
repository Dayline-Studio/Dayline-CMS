<?php
function socialbar()
{
    $title = 'Follow us';
        $content = show("panels/socialbar", array(
            'twitter' => Config::$settings->link_twitter,
            'facebook' => Config::$settings->link_facebook,
            'youtube' => Config::$settings->link_youtube,
            'google' => Config::$settings->link_google
            ));
    return show("panels/box", array("content" => $content, "title" => $title, "name" => __FUNCTION__));
}
