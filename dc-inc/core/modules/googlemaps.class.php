<?php

class GoogleMaps extends MainModule
{

    public $y_size = 200, $zoom = 14, $query = "Deutschland";

    protected function render()
    {
        $file = 'site/modules/googlemaps_show';
        $set = get_object_vars($this);
        $set['query'] = preg_replace("/\s+/", " ", $this->query);
        $set['query'] = str_replace(' ', '+', $set['query']);
        return show($file, $set);
    }

    protected function render_admin()
    {
        $file = 'site/modules/googlemaps_admin';
        return show($file, get_object_vars($this));
    }
}