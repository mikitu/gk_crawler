<?php

namespace seeds\ds;

class Lidl
{
    public $headers = [];
    public $data = [];
    public $method = 'GET';
    public $name = 'Lidl';
    public $country_code = 'UK';
    public $url = 'https://spatial.virtualearth.net/REST/v1/data/588775718a4b4312842f6dffb4428cff/Filialdaten-UK/Filialdaten-UK?spatialFilter=nearby(51.50642013549805,-0.12721000611782074,999)&$select=Latitude,Longitude,AddressLine,PostalCode,Locality,OpeningTimes,ShownStoreName&$top=50&$format=json&key=Argt0lKZTug_IDWKC5e8MWmasZYNJPRs0btLw62Vnwd7VLxhOxFLW2GfwAhMK5Xg';
}
