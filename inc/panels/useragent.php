<?php
function useragent() {
    UserAgent::read();
    $set['ip'] =  UserAgent::$ip;
    $set['browser'] = UserAgent::$browser;
    $set['os'] = UserAgent::$os;
    return show('panels/user_agent', $set);
}