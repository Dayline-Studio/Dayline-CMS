<?php
function socialbar()
{
        return show("panels/socialbar", array(
            'twitter' => Config::$settings->link_twitter,
            'facebook' => Config::$settings->link_facebook,
            'youtube' => Config::$settings->link_youtube,
            'google' => Config::$settings->link_google
            ));
}
