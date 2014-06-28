<?php
function user_agent() {
    UserAgent::read();
    echo UserAgent::$agent;
    $set['ip'] =  UserAgent::$ip;
    $set['browser'] = UserAgent::$browser;
    $set['os'] = UserAgent::$os;
    return show('panels/user_agent', $set);
}