<?php
function useragent()
{
    ClientAgent::read();
    $set['ip'] = ClientAgent::$ip;
    $set['browser'] = ClientAgent::$browser;
    $set['os'] = ClientAgent::$os;
    return show('panels/user_agent', $set);
}