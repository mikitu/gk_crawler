<?php

namespace seeds\ds;

class Sainsburys
{
    public $headers = [];
    public $data = [];
    public $method = 'GET';
    public $name = 'Sainsburys';
    public $country_code = 'UK';
    public $url = 'https://api.stores.sainsburys.co.uk/v1/stores/?&store_type=main%2Clocal&within=&sort=&limit=50&summary=false';
}
