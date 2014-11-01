<?php

class GoogleMaps extends ModuleModel
{

    public $y_size = 200, $zoom = 14, $query = "Deutschland";

    protected function render()
    {
        $file = 'site/modules/googlemaps_show';
        $set = get_object_vars($this);
        $query = preg_replace("/\s+/", " ", $this->query);
        $query = str_replace(' ', '+', $query);

        $request = json_decode(file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=$query"));

        if ($request->status == "OK") {
            $map['lat'] = $request->results[0]->geometry->location->lat;
            $map['lng'] = $request->results[0]->geometry->location->lng;
            $map['zoom'] = $this->zoom;
            $map['y_size'] = $this->y_size;
        }

        return show($file, $map);
    }

    protected function render_admin()
    {
        $file = 'site/modules/googlemaps_admin';
        return show($file, get_object_vars($this));
    }
}